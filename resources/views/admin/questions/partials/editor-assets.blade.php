<link href="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.snow.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/mathlive/dist/mathlive.min.js"></script>

<style>
    .ql-editor { min-height: 170px; font-size: 0.95rem; }
    .ql-toolbar.ql-snow, .ql-container.ql-snow { border-color: #d1d5db; }
    .ql-toolbar.ql-snow { border-top-left-radius: 0.5rem; border-top-right-radius: 0.5rem; }
    .ql-container.ql-snow { border-bottom-left-radius: 0.5rem; border-bottom-right-radius: 0.5rem; }
    .ql-editor img, .ql-editor video { max-width: 100%; }
    math-field { width: 100%; font-size: 1.4rem; padding: 10px; border: 1px solid #d1d5db; border-radius: 0.5rem; }
</style>

<!-- Popup editor rumus (MathLive) -->
<div id="math-modal" style="display:none;" class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg p-5">
        <h3 class="text-lg font-bold text-gray-800 mb-3"><i class="fa-solid fa-square-root-variable text-green-600"></i> Sisipkan Rumus</h3>
        <math-field id="math-input"></math-field>
        <p class="text-xs text-gray-500 mt-2">Klik <b>Keyboard Simbol</b> untuk memilih α, β, Δ, Σ, akar, pecahan, integral, dll. Bisa juga ketik langsung (mis. <code>\alpha</code>, <code>x^2</code>, <code>\frac{a}{b}</code>).</p>
        <div class="flex items-center justify-between mt-4">
            <button type="button" id="math-keyboard-btn" class="px-3 py-2 text-sm rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700"><i class="fa-solid fa-keyboard"></i> Keyboard Simbol</button>
            <div class="flex gap-2">
                <button type="button" id="math-cancel" class="px-4 py-2 text-sm rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700">Batal</button>
                <button type="button" id="math-insert" class="px-5 py-2 text-sm rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold">Sisipkan</button>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    const CSRF = '<?= csrf_token() ?>';
    const UPLOAD_URL = '<?= route('admin.media.upload') ?>';
    const BlockEmbed = Quill.import('blots/block/embed');

    class AudioBlot extends BlockEmbed {
        static create(url) {
            const node = super.create();
            node.setAttribute('src', url);
            node.setAttribute('controls', true);
            node.setAttribute('style', 'width:100%;margin:8px 0;');
            return node;
        }
        static value(node) { return node.getAttribute('src'); }
    }
    AudioBlot.blotName = 'audio';
    AudioBlot.tagName = 'audio';
    Quill.register(AudioBlot);

    class VideoFileBlot extends BlockEmbed {
        static create(url) {
            const node = super.create();
            node.setAttribute('src', url);
            node.setAttribute('controls', true);
            node.setAttribute('style', 'max-width:100%;margin:8px 0;');
            return node;
        }
        static value(node) { return node.getAttribute('src'); }
    }
    VideoFileBlot.blotName = 'videofile';
    VideoFileBlot.tagName = 'video';
    Quill.register(VideoFileBlot);

    async function uploadFile(file) {
        const fd = new FormData();
        fd.append('file', file);
        const res = await fetch(UPLOAD_URL, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            body: fd,
        });
        if (!res.ok) { alert('Gagal upload file (cek ukuran/format).'); return null; }
        const data = await res.json();
        return data.location;
    }

    function pickAndUpload(accept, onDone) {
        const input = document.createElement('input');
        input.type = 'file';
        input.accept = accept;
        input.onchange = async () => {
            if (!input.files.length) return;
            const url = await uploadFile(input.files[0]);
            if (url) onDone(url);
        };
        input.click();
    }

    function initQuill(editorSel, hiddenSel) {
        const hidden = document.querySelector(hiddenSel);
        const quill = new Quill(editorSel, {
            theme: 'snow',
            placeholder: 'Tulis di sini... (bisa sisipkan gambar, video, audio, dan rumus)',
            modules: {
                formula: true,
                toolbar: [
                    [{ header: [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ list: 'ordered' }, { list: 'bullet' }],
                    ['blockquote', 'code-block'],
                    ['link', 'image', 'video', 'formula'],
                    ['clean'],
                ],
            },
        });

        quill.getModule('toolbar').addHandler('image', function () {
            pickAndUpload('image/*', (url) => {
                const range = quill.getSelection(true);
                quill.insertEmbed(range.index, 'image', url, 'user');
                quill.setSelection(range.index + 1);
            });
        });

        // Override tombol formula -> buka popup MathLive
        quill.getModule('toolbar').addHandler('formula', function () {
            if (window.__openMath) window.__openMath(quill);
        });

        const container = document.querySelector(editorSel).parentElement;
        const bar = document.createElement('div');
        bar.className = 'flex gap-2 mt-2';
        bar.innerHTML =
            '<button type="button" class="px-3 py-1.5 text-xs rounded bg-gray-100 hover:bg-gray-200 text-gray-700"><i class="fa-solid fa-volume-high"></i> Audio</button>' +
            '<button type="button" class="px-3 py-1.5 text-xs rounded bg-gray-100 hover:bg-gray-200 text-gray-700"><i class="fa-solid fa-film"></i> Video (file)</button>';
        container.appendChild(bar);
        const btns = bar.querySelectorAll('button');
        btns[0].addEventListener('click', () => pickAndUpload('audio/*', (url) => {
            const r = quill.getSelection(true);
            quill.insertEmbed(r.index, 'audio', url, 'user');
        }));
        btns[1].addEventListener('click', () => pickAndUpload('video/*', (url) => {
            const r = quill.getSelection(true);
            quill.insertEmbed(r.index, 'videofile', url, 'user');
        }));

        const sync = () => { hidden.value = quill.root.innerHTML; };
        quill.on('text-change', sync);
        sync();
        const form = hidden.closest('form');
        if (form) form.addEventListener('submit', sync);
    }

    document.addEventListener('DOMContentLoaded', function () {
        // Setup MathLive
        if (window.MathfieldElement) {
            MathfieldElement.fontsDirectory = 'https://cdn.jsdelivr.net/npm/mathlive/dist/fonts';
            MathfieldElement.soundsDirectory = null;
        }
        const mathModal = document.getElementById('math-modal');
        const mathInput = document.getElementById('math-input');
        let activeQuill = null, savedRange = null;

        window.__openMath = function (quill) {
            activeQuill = quill;
            savedRange = quill.getSelection();
            if (mathInput.setValue) mathInput.setValue(''); else mathInput.value = '';
            mathModal.style.display = 'flex';
            setTimeout(() => mathInput.focus(), 50);
        };
        function closeMath() { mathModal.style.display = 'none'; }

        document.getElementById('math-cancel').onclick = closeMath;
        document.getElementById('math-keyboard-btn').onclick = function () {
            if (window.mathVirtualKeyboard) window.mathVirtualKeyboard.show();
            mathInput.focus();
        };
        document.getElementById('math-insert').onclick = function () {
            const latex = (mathInput.getValue ? mathInput.getValue('latex') : (mathInput.value || '')).trim();
            if (latex && activeQuill) {
                const idx = savedRange ? savedRange.index : activeQuill.getLength();
                activeQuill.insertEmbed(idx, 'formula', latex, 'user');
                activeQuill.setSelection(idx + 1, 0);
            }
            closeMath();
        };

        if (document.querySelector('#question-editor')) initQuill('#question-editor', '#question-input');
        if (document.querySelector('#answer-editor')) initQuill('#answer-editor', '#answer-input');

        const typeSel = document.getElementById('type-select');
        const pg = document.getElementById('pg-options');
        const essay = document.getElementById('essay-answer');
        function toggle() {
            const t = typeSel.value;
            if (pg) pg.style.display = (t === 'pilihan_ganda') ? '' : 'none';
            if (essay) essay.style.display = (t === 'essay') ? '' : 'none';
        }
        if (typeSel) { toggle(); typeSel.addEventListener('change', toggle); }
    });
})();
</script>