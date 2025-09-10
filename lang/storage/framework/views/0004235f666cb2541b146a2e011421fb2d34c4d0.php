<?php $__env->startSection('pageTitle', __('dashboard.terms_&_conditions')); ?>


<?php $__env->startSection('content'); ?>
<div class="post d-flex flex-column-fluid" id="kt_post">
    <!--begin::Container-->
    <div id="kt_content_container" class="container-xxl">
        <!--begin::Basic info-->
        <div class="card mb-5 mb-xl-10">
            <!--begin::Card header-->
            <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
                data-bs-target="#kt_account_profile_details" aria-expanded="true"
                aria-controls="kt_account_profile_details">
                <!--begin::Card title-->
                <div class="card-title m-0">
                    <h3 class="fw-bolder m-0">
                        <?php echo e(isset($termsSittng) ? __('dashboard.update_terms_setting') : __('dashboard.create_terms_setting')); ?>

                    </h3>
                </div>
                <!--end::Card title-->
            </div>
            <!--end::Card header-->

            <!--begin::Content-->
            <div id="kt_account_settings_profile_details" class="collapse show">

                <form
                    action="<?php echo e(isset($termsSittng) ? route('terms_sittngs.update', $termsSittng->id) : route('terms_sittngs.store')); ?>"
                    method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <?php if(isset($termsSittng)): ?>
                        <?php echo method_field('PUT'); ?>
                    <?php endif; ?>
                    <div class="card-body border-top p-9">
                        <div class="row">
                            <!-- Commercial License Field - Arabic (Quill) -->
                            <div class="form-group col-md-6 mb-3">
                                <label for="quill_ar" class="form-label"><?php echo e(__('dashboard.commercial_license')); ?> (<?php echo e(__('dashboard.arabic')); ?>)</label>
                                <div id="quill_ar" class="quill-container"></div>
                                <textarea name="commercial_license_ar" id="commercial_license_ar" class="d-none"><?php echo e(isset($termsSittng->commercial_license_ar) ? $termsSittng->commercial_license_ar : ''); ?></textarea>
                            </div>

                            <!-- Commercial License Field - English (Quill) -->
                            <div class="form-group col-md-6 mb-3">
                                <label for="quill_en" class="form-label"><?php echo e(__('dashboard.commercial_license')); ?> (<?php echo e(__('dashboard.english')); ?>)</label>
                                <div id="quill_en" class="quill-container"></div>
                                <textarea name="commercial_license_en" id="commercial_license_en" class="d-none"><?php echo e(isset($termsSittng->commercial_license_en) ? $termsSittng->commercial_license_en : ''); ?></textarea>
                            </div>

                        </div>
                        <!-- Submit Button -->
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <?php echo e(__('dashboard.update_terms_setting')); ?>

                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <!--end::Content-->
        </div>
        <!--end::Basic info-->
    </div>
    <!--end::Container-->
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('css'); ?>
<!-- QuillJS styles -->
<link href="https://unpkg.com/quill@1.3.7/dist/quill.snow.css" rel="stylesheet">
<!-- Google Fonts for Arabic -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&family=Amiri:wght@400;700&display=swap" rel="stylesheet">

<style>
    .quill-container {
        background: #fff;
        border: 1px solid #e4e6ea;
        border-radius: 0.475rem;
    margin-bottom: 1rem;
    }

    .ql-toolbar.ql-snow {
        border: 0;
        border-bottom: 1px solid #e4e6ea;
        border-radius: 0.475rem 0.475rem 0 0;
        background: #fff;
        padding: 10px;
    }

    .ql-container.ql-snow {
        border: 0;
        border-radius: 0 0 0.475rem 0.475rem;
        min-height: 220px;
    overflow: visible;
    }

    .ql-editor {
        min-height: 200px;
        font-size: 14px;
        line-height: 1.6;
        padding: 1rem;
    }

    /* Arabic RTL styles */
    #quill_ar .ql-editor {
        direction: rtl;
        text-align: right;
        font-family: 'Cairo', 'Amiri', Arial, sans-serif;
    }

    /* English LTR styles */
    #quill_en .ql-editor {
        direction: ltr;
        text-align: left;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;
    }

    /* Table styling - ensure visible borders including bottom */
    .ql-editor table {
        border-collapse: collapse;
        border-spacing: 0;
        width: 100%;
        margin: 10px 0;
    }
    .ql-editor table,
    .ql-editor th,
    .ql-editor td {
        border: 1px solid #dee2e6;
    }
    .ql-editor th,
    .ql-editor td {
        padding: 8px 12px;
        background: #fff;
        vertical-align: top;
    }
    .ql-editor th {
        background: #f8f9fa;
        font-weight: 700;
        border-bottom: 2px solid #dee2e6;
    }

    /* Dropdowns and pickers */
    .ql-snow .ql-picker {
        color: #495057;
    }
    .ql-snow .ql-picker-options {
        border: 1px solid #e4e6ea;
        border-radius: 6px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.08);
    }

    /* Make toolbar buttons clearer */
    .ql-snow .ql-stroke { stroke: #495057; }
    .ql-snow .ql-fill { fill: #495057; }
    .ql-snow .ql-picker-label.ql-active,
    .ql-snow .ql-picker-item.ql-selected,
    .ql-snow .ql-toolbar .ql-picker-label:hover,
    .ql-snow .ql-toolbar .ql-picker-item:hover,
    .ql-snow .ql-toolbar button:hover .ql-stroke {
        stroke: #0d6efd;
    }

    /* RTL list fixes: ensure caret appears after bullet/number in Arabic */
    #quill_ar .ql-editor[dir="rtl"] ol,
    #quill_ar .ql-editor[dir="rtl"] ul {
        padding-right: 1.5em;
        padding-left: 0;
    }
    #quill_ar .ql-editor[dir="rtl"] li {
        direction: rtl;
        text-align: right;
        list-style: none; /* Quill uses pseudo markers */
    }
    #quill_ar .ql-editor[dir="rtl"] li::before {
        float: right;
        margin-right: -1.5em; /* pull marker to the right */
        margin-left: .3em;
        text-align: right;
    }

    /* Ensure Quill tooltip (link editor) is visible and inputs are wide */
    .ql-snow .ql-tooltip { z-index: 1060; }
    .ql-snow .ql-tooltip input[type="text"] { min-width: 260px; }

    /* Prevent font/size labels from overlapping the arrow */
    .ql-snow .ql-picker-label { position: relative; padding-right: 28px; }
    .ql-snow .ql-picker-label::after { right: 6px; }
    .ql-snow .ql-picker-label::before { display: inline-block; max-width: calc(100% - 24px); overflow: hidden; text-overflow: ellipsis; vertical-align: middle; }

    /* Ensure submit button section stays visible above editors */
    .card-body .form-group:last-child {
        margin-top: 0.75rem;
        position: relative;
        z-index: 5;
    }

    /* Ensure Quill tooltip (link editor) shows above modals/toolbars */
    .ql-snow .ql-tooltip {
        z-index: 1060;
    }
    .ql-snow .ql-tooltip input[type="text"] {
        min-width: 260px;
    }

    /* Quill size picker labels (fix showing all as Normal) */
    .ql-snow .ql-picker.ql-size .ql-picker-item[data-value="10px"]::before,
    .ql-snow .ql-picker.ql-size .ql-picker-label[data-value="10px"]::before { content: '10'; }
    .ql-snow .ql-picker.ql-size .ql-picker-item[data-value="11px"]::before,
    .ql-snow .ql-picker.ql-size .ql-picker-label[data-value="11px"]::before { content: '11'; }
    .ql-snow .ql-picker.ql-size .ql-picker-item[data-value="12px"]::before,
    .ql-snow .ql-picker.ql-size .ql-picker-label[data-value="12px"]::before { content: '12'; }
    .ql-snow .ql-picker.ql-size .ql-picker-item[data-value="14px"]::before,
    .ql-snow .ql-picker.ql-size .ql-picker-label[data-value="14px"]::before { content: '14'; }
    .ql-snow .ql-picker.ql-size .ql-picker-item[data-value="16px"]::before,
    .ql-snow .ql-picker.ql-size .ql-picker-label[data-value="16px"]::before { content: '16'; }
    .ql-snow .ql-picker.ql-size .ql-picker-item[data-value="18px"]::before,
    .ql-snow .ql-picker.ql-size .ql-picker-label[data-value="18px"]::before { content: '18'; }
    .ql-snow .ql-picker.ql-size .ql-picker-item[data-value="20px"]::before,
    .ql-snow .ql-picker.ql-size .ql-picker-label[data-value="20px"]::before { content: '20'; }
    .ql-snow .ql-picker.ql-size .ql-picker-item[data-value="22px"]::before,
    .ql-snow .ql-picker.ql-size .ql-picker-label[data-value="22px"]::before { content: '22'; }
    .ql-snow .ql-picker.ql-size .ql-picker-item[data-value="24px"]::before,
    .ql-snow .ql-picker.ql-size .ql-picker-label[data-value="24px"]::before { content: '24'; }
    .ql-snow .ql-picker.ql-size .ql-picker-item[data-value="26px"]::before,
    .ql-snow .ql-picker.ql-size .ql-picker-label[data-value="26px"]::before { content: '26'; }
    .ql-snow .ql-picker.ql-size .ql-picker-item[data-value="28px"]::before,
    .ql-snow .ql-picker.ql-size .ql-picker-label[data-value="28px"]::before { content: '28'; }
    .ql-snow .ql-picker.ql-size .ql-picker-item[data-value="30px"]::before,
    .ql-snow .ql-picker.ql-size .ql-picker-label[data-value="30px"]::before { content: '30'; }
    .ql-snow .ql-picker.ql-size .ql-picker-item[data-value="36px"]::before,
    .ql-snow .ql-picker.ql-size .ql-picker-label[data-value="36px"]::before { content: '36'; }
    .ql-snow .ql-picker.ql-size .ql-picker-item[data-value="48px"]::before,
    .ql-snow .ql-picker.ql-size .ql-picker-label[data-value="48px"]::before { content: '48'; }
    .ql-snow .ql-picker.ql-size .ql-picker-item[data-value="64px"]::before,
    .ql-snow .ql-picker.ql-size .ql-picker-label[data-value="64px"]::before { content: '64'; }

    /* Quill font picker labels and previews */
    .ql-snow .ql-picker.ql-font .ql-picker-item[data-value="Cairo"]::before,
    .ql-snow .ql-picker.ql-font .ql-picker-label[data-value="Cairo"]::before { content: 'Cairo'; font-family: 'Cairo', 'Amiri', Arial, sans-serif; }
    .ql-snow .ql-picker.ql-font .ql-picker-item[data-value="Amiri"]::before,
    .ql-snow .ql-picker.ql-font .ql-picker-label[data-value="Amiri"]::before { content: 'Amiri'; font-family: 'Amiri', 'Cairo', serif; }
    .ql-snow .ql-picker.ql-font .ql-picker-item[data-value="Arial"]::before,
    .ql-snow .ql-picker.ql-font .ql-picker-label[data-value="Arial"]::before { content: 'Arial'; font-family: Arial, sans-serif; }
    .ql-snow .ql-picker.ql-font .ql-picker-item[data-value="Times New Roman"]::before,
    .ql-snow .ql-picker.ql-font .ql-picker-label[data-value="Times New Roman"]::before { content: 'Times New Roman'; font-family: 'Times New Roman', Times, serif; }
    .ql-snow .ql-picker.ql-font .ql-picker-item[data-value="Tahoma"]::before,
    .ql-snow .ql-picker.ql-font .ql-picker-label[data-value="Tahoma"]::before { content: 'Tahoma'; font-family: Tahoma, sans-serif; }
    .ql-snow .ql-picker.ql-font .ql-picker-item[data-value="Verdana"]::before,
    .ql-snow .ql-picker.ql-font .ql-picker-label[data-value="Verdana"]::before { content: 'Verdana'; font-family: Verdana, sans-serif; }
    .ql-snow .ql-picker.ql-font .ql-picker-item[data-value="Helvetica"]::before,
    .ql-snow .ql-picker.ql-font .ql-picker-label[data-value="Helvetica"]::before { content: 'Helvetica'; font-family: Helvetica, Arial, sans-serif; }
    .ql-snow .ql-picker.ql-font .ql-picker-item[data-value="Courier New"]::before,
    .ql-snow .ql-picker.ql-font .ql-picker-label[data-value="Courier New"]::before { content: 'Courier New'; font-family: 'Courier New', Courier, monospace; }
    .ql-snow .ql-picker.ql-font .ql-picker-item[data-value="Noto Sans Arabic"]::before,
    .ql-snow .ql-picker.ql-font .ql-picker-label[data-value="Noto Sans Arabic"]::before { content: 'Noto Sans Arabic'; font-family: 'Noto Sans Arabic', 'Cairo', sans-serif; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('js'); ?>
<!-- QuillJS scripts -->
<script src="https://unpkg.com/quill@1.3.7/dist/quill.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Configure Quill size and font whitelists
    var Size = Quill.import('attributors/style/size');
    Size.whitelist = ['10px','11px','12px','14px','16px','18px','20px','22px','24px','26px','28px','30px','36px','48px','64px'];
    Quill.register(Size, true);

    var Font = Quill.import('attributors/style/font');
    Font.whitelist = ['Cairo','Amiri','Arial','Times New Roman','Tahoma','Verdana','Helvetica','Courier New','Noto Sans Arabic'];
    Quill.register(Font, true);

    // Register style attributors so HTML stores inline styles
    var Align = Quill.import('attributors/style/align');
    var Direction = Quill.import('attributors/style/direction');
    var Color = Quill.import('attributors/style/color');
    var Background = Quill.import('attributors/style/background');
    Quill.register(Align, true);
    Quill.register(Direction, true);
    Quill.register(Color, true);
    Quill.register(Background, true);


    var toolbarOptions = [
        [{ 'font': Font.whitelist }],
        [{ 'size': Size.whitelist }],
        ['bold', 'italic', 'underline', 'strike', 'blockquote'],
        [{ 'color': [] }, { 'background': [] }],
        [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
        [{ 'list': 'ordered' }, { 'list': 'bullet' }],
        [{ 'align': [] }],
    ['link', 'clean']
    ];

    function buildQuill(elementId, opts) {
        var q = new Quill(elementId, {
            theme: 'snow',
            placeholder: opts.placeholder || '',
            formats: ['bold','italic','underline','strike','blockquote','color','background','header','list','align','link','font','size','direction'],
            modules: {
                toolbar: toolbarOptions,
                history: { delay: 500, maxStack: 500, userOnly: true }
            }
        });

        // Default directions and fonts
        if (opts.rtl) {
            q.format('direction', 'rtl');
            q.format('align', 'right');
            q.format('font', 'Cairo');
            q.format('size', '14px');
            q.root.setAttribute('dir', 'rtl');
        } else {
            q.format('direction', 'ltr');
            q.format('align', 'left');
            q.format('size', '14px');
        }

        // Custom toolbar handlers
        var toolbar = q.getModule('toolbar');

        function normalizeUrl(url) {
            if (!url) return '';
            var trimmed = url.trim();
            if (!/^https?:\/\//i.test(trimmed) && !/^mailto:/i.test(trimmed)) {
                trimmed = 'https://' + trimmed;
            }
            return trimmed;
        }

        // Link handler with prompt (works even if no text is selected)
        toolbar.addHandler('link', function(value) {
            var range = q.getSelection(true);
            if (!range) return;
            if (value) {
                var current = q.getFormat(range);
                var existing = current.link || '';
                var url = window.prompt('Enter URL', existing || 'https://');
                url = normalizeUrl(url);
                if (url) {
                    if (range.length === 0) {
                        q.insertText(range.index, url, 'link', url);
                        q.setSelection(range.index + url.length, 0);
                    } else {
                        q.format('link', url);
                    }
                    setTimeout(function() {
                        q.root.querySelectorAll('a[href]').forEach(function(a){
                            a.setAttribute('target','_blank');
                            a.setAttribute('rel','noopener noreferrer');
                        });
                    }, 0);
                } else {
                    q.format('link', false);
                }
            } else {
                q.format('link', false);
            }
        });

        // Clear formatting handler
        toolbar.addHandler('clean', function() {
            var range = q.getSelection(true);
            if (range) {
                q.removeFormat(range.index, range.length);
            }
        });

    // Removed insertTable handler and dependency

        return q;
    }

    // Initialize editors
    var quillAr = buildQuill('#quill_ar', { rtl: true, placeholder: 'اكتب الشروط والأحكام باللغة العربية...' });
    var quillEn = buildQuill('#quill_en', { rtl: false, placeholder: 'Write terms and conditions in English...' });

    // Load initial content from hidden textareas
    var initialAr = <?php echo json_encode(old('commercial_license_ar', isset($termsSittng) ? ($termsSittng->commercial_license_ar ?? '') : ''), 512) ?>;
    var initialEn = <?php echo json_encode(old('commercial_license_en', isset($termsSittng) ? ($termsSittng->commercial_license_en ?? '') : ''), 512) ?>;
    if (initialAr) quillAr.clipboard.dangerouslyPasteHTML(initialAr);
    if (initialEn) quillEn.clipboard.dangerouslyPasteHTML(initialEn);

    // Ensure colors/sizes apply as inline styles for persistence
    quillAr.root.style.fontFamily = 'Cairo, Amiri, Arial, sans-serif';
    quillEn.root.style.fontFamily = 'Arial, sans-serif';

    // Real-time sync: copy HTML to hidden fields without depending on form submit
    function syncEditorsToHidden() {
        var arField = document.getElementById('commercial_license_ar');
        var enField = document.getElementById('commercial_license_en');
        if (arField) arField.value = quillAr.root.innerHTML;
        if (enField) enField.value = quillEn.root.innerHTML;
    }
    // Initial sync (after loading any initial content)
    syncEditorsToHidden();
    // Sync on changes and blur
    quillAr.on('text-change', syncEditorsToHidden);
    quillEn.on('text-change', syncEditorsToHidden);
    quillAr.root.addEventListener('blur', syncEditorsToHidden);
    quillEn.root.addEventListener('blur', syncEditorsToHidden);
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('dashboard.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\my projects\icamp-reservations\resources\views/dashboard/TermsSittngs/create.blade.php ENDPATH**/ ?>