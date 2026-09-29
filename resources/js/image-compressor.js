/**
 * Client-Side Automatic Image Compressor for FRC Reporting System
 * Automatically intercepts file uploads for images, resizes and compresses
 * large camera photos (> 1.2MB) to crisp Web/JPEG (< 1.5MB) via HTML5 Canvas.
 */

function formatBytes(bytes) {
    if (bytes === 0) return '0 B';
    const k = 1024;
    const sizes = ['B', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return (bytes / Math.pow(k, i)).toFixed(1) + ' ' + sizes[i];
}

function compressSingleImage(file, maxWidth = 1600, maxHeight = 1600, quality = 0.82) {
    return new Promise((resolve) => {
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = (event) => {
            const img = new Image();
            img.src = event.target.result;
            img.onload = () => {
                let { width, height } = img;

                // Scale down dimensions proportionally if exceeding maxWidth/maxHeight
                if (width > maxWidth || height > maxHeight) {
                    if (width > height) {
                        height = Math.round((height * maxWidth) / width);
                        width = maxWidth;
                    } else {
                        width = Math.round((width * maxHeight) / height);
                        height = maxHeight;
                    }
                }

                const canvas = document.createElement('canvas');
                canvas.width = width;
                canvas.height = height;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0, width, height);

                // Convert to JPEG for optimal file size (PNG can be massive for camera photos)
                const mimeType = 'image/jpeg';

                canvas.toBlob((blob) => {
                    if (!blob) {
                        resolve(file);
                        return;
                    }

                    const originalName = file.name.replace(/\.[^/.]+$/, "");
                    const compressedFile = new File([blob], `${originalName}.jpg`, {
                        type: mimeType,
                        lastModified: Date.now()
                    });

                    resolve(compressedFile);
                }, mimeType, quality);
            };
            img.onerror = () => resolve(file);
        };
        reader.onerror = () => resolve(file);
    });
}

export async function processImageUpload(inputElement) {
    const file = inputElement.files && inputElement.files[0];
    if (!file || !file.type.startsWith('image/')) return;

    // Threshold: if file is already <= 1.2MB, keep it
    const needsCompression = file.size > 1.2 * 1024 * 1024;
    if (!needsCompression) {
        showFeedback(inputElement, `✓ File terpilih: ${file.name} (${formatBytes(file.size)})`, 'normal');
        return;
    }

    const form = inputElement.closest('form');
    const submitBtn = form ? form.querySelector('button[type="submit"]') : null;
    const originalBtnText = submitBtn ? submitBtn.innerHTML : '';

    try {
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-75', 'cursor-wait');
        }

        showFeedback(inputElement, `Mengompresi foto (${formatBytes(file.size)})...`, 'processing');

        let quality = 0.82;
        let maxDim = 1600;
        let compressed = await compressSingleImage(file, maxDim, maxDim, quality);

        // Multi-pass safety: ensure output is comfortably below Laravel's 2MB (2048 KB) limit
        const targetMaxBytes = 1.8 * 1024 * 1024; // 1.8MB
        while (compressed.size > targetMaxBytes && quality > 0.4) {
            quality -= 0.15;
            maxDim = Math.round(maxDim * 0.85);
            compressed = await compressSingleImage(file, maxDim, maxDim, quality);
        }

        // Assign back to input using DataTransfer
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(compressed);
        inputElement.files = dataTransfer.files;

        showFeedback(
            inputElement,
            `✓ Foto otomatis dioptimasi: ${formatBytes(file.size)} → ${formatBytes(compressed.size)} (Siap diunggah)`,
            'success'
        );
    } catch (err) {
        console.warn('[ImageCompressor] Gagal mengompresi gambar di client:', err);
    } finally {
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-75', 'cursor-wait');
            submitBtn.innerHTML = originalBtnText;
        }
    }
}

function showFeedback(inputElement, message, type = 'normal') {
    let containerId = inputElement.id ? `compress-info-${inputElement.id}` : `compress-info-${inputElement.name}`;
    let feedbackEl = document.getElementById(containerId);

    if (!feedbackEl) {
        feedbackEl = document.createElement('div');
        feedbackEl.id = containerId;
        feedbackEl.className = 'mt-2 text-xs font-medium transition-all duration-200';
        inputElement.parentNode.parentNode.appendChild(feedbackEl);
    }

    if (type === 'processing') {
        feedbackEl.className = 'mt-2 text-xs font-medium text-amber-600 dark:text-amber-400 flex items-center gap-1.5 animate-pulse';
        feedbackEl.innerHTML = `
            <svg class="animate-spin h-3.5 w-3.5 text-amber-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>${message}</span>
        `;
    } else if (type === 'success') {
        feedbackEl.className = 'mt-2 text-xs font-medium text-emerald-600 dark:text-emerald-400 flex items-center gap-1.5';
        feedbackEl.innerHTML = `
            <svg class="h-3.5 w-3.5 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            <span>${message}</span>
        `;
    } else {
        feedbackEl.className = 'mt-2 text-xs font-medium text-indigo-600 dark:text-indigo-400';
        feedbackEl.textContent = message;
    }
}

// Auto-bind to all image file inputs across the app
document.addEventListener('DOMContentLoaded', () => {
    document.addEventListener('change', (e) => {
        const target = e.target;
        if (target && target.tagName === 'INPUT' && target.type === 'file') {
            const isImageInput = target.accept && (target.accept.includes('image') || target.accept.includes('jpg') || target.accept.includes('png'));
            const isFotoField = target.name && target.name.startsWith('foto');
            if (isImageInput || isFotoField) {
                processImageUpload(target);
            }
        }
    });
});
