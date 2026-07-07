<link href="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.snow.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/codemirror.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/theme/material-darker.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/mathlive/dist/mathlive.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/codemirror.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/python/python.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/javascript/javascript.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/clike/clike.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/edit/closebrackets.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/edit/matchbrackets.min.js"></script>

<style>
    .ql-editor {
        min-height: 170px;
        font-size: 0.95rem;
    }

     .ql-toolbar.ql-snow,
    .ql-container.ql-snow {
        border-color: #d1d5db;
    }

     .ql-toolbar.ql-snow {
        border-top-left-radius: 0.5rem;
        border-top-right-radius: 0.5rem;
    }

     .ql-container.ql-snow {
        border-bottom-left-radius: 0.5rem;
        border-bottom-right-radius: 0.5rem;
    }

     .ql-editor img,
    .ql-editor video {
        max-width: 100%;
    }

     math-field {
        width: 100%;
        font-size: 1.4rem;
        padding: 10px;
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
    }

     .CodeMirror {
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        height: 280px;
        font-size: 0.9rem;
    }
</style>

<!-- Popup editor rumus (MathLive) -->
<div id="math-modal" style="display:none;" class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg p-5">
        <h3 class="text-lg font-bold text-gray-800 mb-3"><i class="fa-solid fa-square-root-variable text-green-600"></i>
            Sisipkan Rumus</h3>
        <math-field id="math-input"></math-field>
        <div class="flex flex-wrap items-center gap-2 mt-3">
            <span class="text-xs text-gray-500">Matriks:</span>
            <button type="button" class="math-mtx px-2.5 py-1 text-xs rounded bg-gray-100 hover:bg-gray-200"
                data-env="pmatrix">( ) 2×2</button>
            <button type="button" class="math-mtx px-2.5 py-1 text-xs rounded bg-gray-100 hover:bg-gray-200"
                data-env="bmatrix">[ ] 2×2</button>
            <button type="button" class="math-mtx px-2.5 py-1 text-xs rounded bg-gray-100 hover:bg-gray-200"
                data-env="vmatrix">| | determinan</button>
            <button type="button" class="math-mtx px-2.5 py-1 text-xs rounded bg-gray-100 hover:bg-gray-200"
                data-env="pmatrix3">( ) 3×3</button>
        </div>
        <p class="text-xs text-gray-500 mt-2">Klik <b>Keyboard Simbol</b> untuk α, β, Δ, Σ, akar, integral, dll.</p>
        <div class="flex items-center justify-between mt-4">
            <button type="button" id="math-keyboard-btn"
                class="px-3 py-2 text-sm rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700"><i
                    class="fa-solid fa-keyboard"></i> Keyboard Simbol</button>
            <div class="flex gap-2">
                <button type="button" id="math-cancel"
                    class="px-4 py-2 text-sm rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700">Batal</button>
                <button type="button" id="math-insert"
                    class="px-5 py-2 text-sm rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold">Sisipkan</button>
            </div>
        </div>
    </div>
</div>

<script>
    (function() {
        const CSRF = '<?= csrf_token() ?>';
        const UPLOAD_URL = '<?= route((request()->routeIs('guru.*') ? 'guru' : 'admin') . '.media.upload') ?>';
        const BlockEmbed = Quill.import('blots/block/embed');
        let cmEditor = null;
        class AudioBlot extends BlockEmbed {
            static create(url) {
                const n = super.create();
                n.setAttribute('src', url);
                n.setAttribute('controls', true);
                n.setAttribute('style', 'width:100%;margin:8px 0;');
                return n;
            }
            static value(node) {
                return node.getAttribute('src');
            }
        }
        AudioBlot.blotName = 'audio';
        AudioBlot.tagName = 'audio';
        Quill.register(AudioBlot);
        class VideoFileBlot extends BlockEmbed {
            static create(url) {
                const n = super.create();
                n.setAttribute('src', url);
                n.setAttribute('controls', true);
                n.setAttribute('style', 'max-width:100%;margin:8px 0;');
                return n;
            }
            static value(node) {
                return node.getAttribute('src');
            }
        }
        VideoFileBlot.blotName = 'videofile';
        VideoFileBlot.tagName = 'video';
        Quill.register(VideoFileBlot);
        async function uploadFile(file) {
            const fd = new FormData();
            fd.append('file', file);
            const res = await fetch(UPLOAD_URL, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': CSRF,
                    'Accept': 'application/json'
                },
                body: fd
            });
            if (!res.ok) {
                alert('Gagal upload file (cek ukuran/format).');
                return null;
            }
            return (await res.json()).location;
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
                        [{
                            header: [1, 2, 3, false]
                        }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{
                            list: 'ordered'
                        }, {
                            list: 'bullet'
                        }],
                        ['blockquote', 'code-block'],
                        ['link', 'image', 'video', 'formula'],
                        ['clean'],
                    ],
                },
            });
            quill.getModule('toolbar').addHandler('image', function() {
                pickAndUpload('image/*', (url) => {
                    const r = quill.getSelection(true);
                    quill.insertEmbed(r.index, 'image', url, 'user');
                    quill.setSelection(r.index + 1);
                });
            });
            quill.getModule('toolbar').addHandler('formula', function() {
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
            const sync = () => {
                hidden.value = quill.root.innerHTML;
            };
            quill.on('text-change', sync);
            sync();
            const form = hidden.closest('form');
            if (form) form.addEventListener('submit', sync);
        }
        function initCodeEditor() {
            const ta = document.getElementById('starter-code');
            if (!ta || typeof CodeMirror === 'undefined') return;
            const langSel = document.getElementById('language-select');
            const modeFor = (v) => v === 'python' ? 'python' : v === 'javascript' ? 'javascript' : v === 'cpp' ?
                'text/x-c++src' : v === 'java' ? 'text/x-java' : 'text/x-csrc';
            cmEditor = CodeMirror.fromTextArea(ta, {
                lineNumbers: true,
                mode: modeFor(langSel ? langSel.value : 'python'),
                indentUnit: 4,
                tabSize: 4,
                theme: 'material-darker',
                indentWithTabs: false, // pakai spasi (mirip Colab), bukan tab
                smartIndent: true, // auto-indent setelah if/for/{ dll
                autoCloseBrackets: true, // ketik ( { [ " ' -> otomatis tutup
                matchBrackets: true, // highlight pasangan kurung
                extraKeys: {
                    Tab: function(cm) {
                        if (cm.somethingSelected()) {
                            cm.indentSelection('add');
                        } else {
                            cm.replaceSelection(Array(cm.getOption('indentUnit') + 1).join(' '), 'end');
                        }
                    }
                }
            });
            if (langSel) langSel.addEventListener('change', () => cmEditor.setOption('mode', modeFor(langSel
                .value)));
            const form = ta.closest('form');
            if (form) form.addEventListener('submit', () => cmEditor.save());
        }
        document.addEventListener('DOMContentLoaded', function() {
            if (window.MathfieldElement) {
                MathfieldElement.fontsDirectory = 'https://cdn.jsdelivr.net/npm/mathlive/dist/fonts';
                MathfieldElement.soundsDirectory = null;
            }
            if (window.mathVirtualKeyboard) {
                window.mathVirtualKeyboard.layouts = ['numeric', 'symbols', 'greek', 'alphabetic'];
            }
            const mathModal = document.getElementById('math-modal');
            const mathInput = document.getElementById('math-input');
            let activeQuill = null,
                savedRange = null;
            window.__openMath = function(quill) {
                activeQuill = quill;
                savedRange = quill.getSelection();
                if (mathInput.setValue) mathInput.setValue('');
                else mathInput.value = '';
                mathModal.style.display = 'flex';
                setTimeout(() => {
                    mathInput.focus();
                    if (window.mathVirtualKeyboard) window.mathVirtualKeyboard.show();
                }, 80);
            };
            function closeMath() {
                mathModal.style.display = 'none';
            }
            document.getElementById('math-cancel').onclick = closeMath;
            document.getElementById('math-keyboard-btn').onclick = function() {
                if (window.mathVirtualKeyboard) window.mathVirtualKeyboard.show();
                mathInput.focus();
            };
            document.getElementById('math-insert').onclick = function() {
                const latex = (mathInput.getValue ? mathInput.getValue('latex') : (mathInput.value ||
                    '')).trim();
                if (latex && activeQuill) {
                    const idx = savedRange ? savedRange.index : activeQuill.getLength();
                    activeQuill.insertEmbed(idx, 'formula', latex, 'user');
                    activeQuill.setSelection(idx + 1, 0);
                }
                closeMath();
            };
            document.querySelectorAll('.math-mtx').forEach(function(b) {
                b.addEventListener('click', function() {
                    const env = b.dataset.env,
                        cell = '\\placeholder{}';
                    const r2 = cell + '&' + cell,
                        r3 = cell + '&' + cell + '&' + cell;
                    let latex = (env === 'pmatrix3') ?
                        '\\begin{pmatrix}' + r3 + '\\\\' + r3 + '\\\\' + r3 +
                        '\\end{pmatrix}' :
                        '\\begin{' + env + '}' + r2 + '\\\\' + r2 + '\\end{' + env + '}';
                    if (mathInput.insert) mathInput.insert(latex, {
                        focus: true
                    });
                    else if (mathInput.executeCommand) mathInput.executeCommand(['insert',
                        latex
                    ]);
                    mathInput.focus();
                });
            });
            if (document.querySelector('#question-editor')) initQuill('#question-editor',
                '#question-input');
            if (document.querySelector('#answer-editor')) initQuill('#answer-editor', '#answer-input');
            initCodeEditor();
            const typeSel = document.getElementById('type-select');
            const pg = document.getElementById('pg-options');
            const essay = document.getElementById('essay-answer');
            const coding = document.getElementById('coding-fields');
            function toggle() {
                const t = typeSel.value;
                if (pg) pg.style.display = (t === 'pilihan_ganda') ? '' : 'none';
                if (essay) essay.style.display = (t === 'essay') ? '' : 'none';
                if (coding) coding.style.display = (t === 'coding') ? '' : 'none';
                if (t === 'coding' && cmEditor) setTimeout(() => cmEditor.refresh(), 50);
            }
            if (typeSel) {
                toggle();
                typeSel.addEventListener('change', toggle);
            }
        });
    })();
</script>
