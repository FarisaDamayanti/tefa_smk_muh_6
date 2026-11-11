<script>
    $(function() {
        const $doc = $(document);
        const $wrap = $('#rekapTableWrap');
        const $form = $('#rekapFilterForm');
        const $search = $form.find('input[name="search"]');
        const $semester = $form.find('select[name="semester"]');

        const $btnApply = $('#btnApplyFilter');
        const $btnReset = $('#btnResetFilter');
        const $btnExport = $('#btnExportExcel');

        const $clientEmptyRow = $('#clientEmptyRow');
        const $serverEmptyRow = $wrap.find('tbody tr.server-empty');

        const $modal = $('#taskModal');
        const $modalTitle = $('#taskModalTaskTitle');
        const $modalDesc = $('#taskModalDesc');

        const apiToken = $('meta[name="api-token"]').attr('content') || '';
        const taskUrlTpl = $('meta[name="task-show-url"]').attr('content') || '';
        const exportHasData = $('meta[name="export-has-data"]').attr('content') === '1';

        // =========================
        // HELPERS
        // =========================
        function toast(type, msg) {
            if (window.toastr && toastr[type]) return toastr[type](msg);
            alert(msg);
        }

        function escapeHtml(str) {
            if (str === null || str === undefined) return '';
            return String(str)
                .replaceAll('&', '&amp;')
                .replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;')
                .replaceAll('"', '&quot;')
                .replaceAll("'", '&#039;');
        }

        function decodeEntities(str) {
            if (str === null || str === undefined) return '';
            const txt = document.createElement('textarea');
            txt.innerHTML = String(str);
            return txt.value;
        }

        function looksLikeHtml(s) {
            return /<\/?[a-z][\s\S]*>/i.test(s);
        }

        function smartDecode(str) {
            let s = (str ?? '').toString();
            const once = decodeEntities(s);
            const twice = decodeEntities(once);
            if (looksLikeHtml(twice)) return twice;
            if (looksLikeHtml(once)) return once;
            return once;
        }

        function showClientEmpty(show) {
            $clientEmptyRow.toggleClass('d-none', !show);
        }

        function buildTaskShowUrl(taskId) {
            if (!taskId) return '';
            if (taskUrlTpl && taskUrlTpl.includes('__TASK__')) return taskUrlTpl.replace('__TASK__', taskId);
            return '';
        }

        // =========================
        // FILTER => SUBMIT TO BE (include ALL fields)
        // =========================
        function submitToBE() {
            const action = $form.attr('action') || window.location.pathname;
            const url = new URL(action, window.location.origin);

            const fd = new FormData($form[0]);
            url.search = '';

            for (const [key, val] of fd.entries()) {
                const v = (val ?? '').toString().trim();
                if (!v) continue;
                url.searchParams.set(key, v);
            }

            url.searchParams.set('_t', Date.now());
            window.location.href = url.toString();
        }

        function resetToBE() {
            const action = $form.attr('action') || window.location.pathname;
            const url = new URL(action, window.location.origin);
            url.search = '';
            window.location.href = url.toString();
        }

        $doc.off('click.rekap', '#btnApplyFilter').on('click.rekap', '#btnApplyFilter', function(e) {
            e.preventDefault();
            submitToBE();
        });

        $search.off('keydown.rekap').on('keydown.rekap', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                submitToBE();
            }
        });

        $doc.off('click.rekap', '#btnResetFilter').on('click.rekap', '#btnResetFilter', function(e) {
            e.preventDefault();
            resetToBE();
        });

        // =========================
        // MODAL
        // =========================
        function openModal() {
            $modal.addClass('is-open').attr('aria-hidden', 'false');
        }

        function closeModal() {
            $modal.removeClass('is-open').attr('aria-hidden', 'true');
        }

        $doc.off('keydown.taskModal').on('keydown.taskModal', function(e) {
            if (e.key === 'Escape' && $modal.hasClass('is-open')) closeModal();
        });

        $doc.off('click.taskModal', '#taskModal').on('click.taskModal', '#taskModal', function(e) {
            if ($(e.target).is('#taskModal')) closeModal();
        });

        // =========================
        // SANITIZE (OPS B) SUPPORT SUMMERNOTE
        // =========================
        const ALLOWED_TAGS = new Set([
            'P', 'BR', 'B', 'STRONG', 'I', 'EM', 'U', 'S', 'DEL',
            'UL', 'OL', 'LI',
            'SPAN', 'DIV',
            'H1', 'H2', 'H3', 'H4', 'H5', 'H6',
            'BLOCKQUOTE',
            'CODE', 'PRE',
            'HR',
            'A', 'IMG',
            'TABLE', 'THEAD', 'TBODY', 'TR', 'TH', 'TD'
        ]);

        const ALLOWED_ATTR = {
            a: new Set(['href', 'target', 'rel', 'title', 'class', 'style']),
            img: new Set(['src', 'alt', 'title', 'width', 'height', 'class', 'style', 'srcset', 'sizes']),
            table: new Set(['class', 'style', 'border', 'cellpadding', 'cellspacing']),
            th: new Set(['colspan', 'rowspan', 'class', 'style']),
            td: new Set(['colspan', 'rowspan', 'class', 'style']),
            '*': new Set(['class', 'style'])
        };

        const ALLOWED_STYLE_PROPS = new Set([
            'text-align', 'font-weight', 'font-style', 'text-decoration', 'white-space',
            'color', 'background-color', 'font-size', 'line-height',
            'margin', 'margin-left', 'margin-right', 'margin-top', 'margin-bottom',
            'padding', 'padding-left', 'padding-right', 'padding-top', 'padding-bottom',
            'border', 'border-left', 'border-right', 'border-top', 'border-bottom',
            'border-color', 'border-style', 'border-width', 'border-collapse',
            'width', 'max-width', 'height'
        ]);

        function sanitizeStyle(styleText) {
            if (!styleText) return '';
            const parts = String(styleText).split(';').map(s => s.trim()).filter(Boolean);

            const safe = [];
            for (const part of parts) {
                const idx = part.indexOf(':');
                if (idx === -1) continue;

                const prop = part.slice(0, idx).trim().toLowerCase();
                const val = part.slice(idx + 1).trim();

                if (!ALLOWED_STYLE_PROPS.has(prop)) continue;

                const vLower = val.toLowerCase();
                if (vLower.includes('javascript:') || vLower.includes('expression(')) continue;

                safe.push(`${prop}: ${val}`);
            }
            return safe.join('; ');
        }

        function isDataAttr(attrLower) {
            return attrLower.startsWith('data-');
        }

        function sanitizeRichText(html) {
            if (!html) return '';

            const tmp = document.createElement('div');
            tmp.innerHTML = String(html);

            tmp.querySelectorAll('script,style,iframe,object,embed').forEach(n => n.remove());

            const nodes = tmp.querySelectorAll('*');
            nodes.forEach(el => {
                const tagUpper = el.tagName;
                const tagLower = tagUpper.toLowerCase();

                if (!ALLOWED_TAGS.has(tagUpper)) {
                    el.replaceWith(...Array.from(el.childNodes));
                    return;
                }

                [...el.attributes].forEach(attr => {
                    const attrLower = attr.name.toLowerCase();

                    if (attrLower.startsWith('on')) {
                        el.removeAttribute(attr.name);
                        return;
                    }

                    const allowData = isDataAttr(attrLower);
                    const allowedForTag = ALLOWED_ATTR[tagLower] || new Set();
                    const allowedGlobal = ALLOWED_ATTR['*'] || new Set();

                    if (!allowData && !allowedForTag.has(attrLower) && !allowedGlobal.has(
                            attrLower)) {
                        el.removeAttribute(attr.name);
                        return;
                    }

                    if (tagLower === 'a' && attrLower === 'href') {
                        const href = (el.getAttribute('href') || '').trim();
                        const lower = href.toLowerCase();
                        if (lower.startsWith('javascript:') || lower.startsWith('vbscript:')) {
                            el.removeAttribute('href');
                        }
                    }

                    if (tagLower === 'a' && el.getAttribute('target') === '_blank') {
                        el.setAttribute('rel', 'noopener noreferrer');
                    }

                    if (tagLower === 'img' && attrLower === 'src') {
                        const src = (el.getAttribute('src') || '').trim();
                        const lower = src.toLowerCase();
                        if (lower.startsWith('javascript:')) el.removeAttribute('src');
                    }

                    if (attrLower === 'style') {
                        const cleanStyle = sanitizeStyle(el.getAttribute('style'));
                        if (cleanStyle) el.setAttribute('style', cleanStyle);
                        else el.removeAttribute('style');
                    }
                });
            });

            return tmp.innerHTML.trim();
        }

        function renderDesc(rawDesc) {
            let s = (rawDesc || '').toString().trim();
            if (!s) return '<span class="text-muted">Deskripsi belum tersedia.</span>';

            s = smartDecode(s).trim();
            if (!s) return '<span class="text-muted">Deskripsi belum tersedia.</span>';

            if (looksLikeHtml(s)) {
                const clean = sanitizeRichText(s);
                return clean ? clean : '<span class="text-muted">Deskripsi belum tersedia.</span>';
            }

            return escapeHtml(s).replace(/\n/g, '<br>');
        }

        // =========================
        // FETCH TASK DETAIL
        // =========================
        const taskDetailCache = {};

        async function fetchTaskDetail(taskId) {
            if (!taskId) return null;
            if (taskDetailCache[taskId]) return taskDetailCache[taskId];

            const url = buildTaskShowUrl(taskId);
            if (!url) return null;

            const res = await fetch(url, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    ...(apiToken ? {
                        'Authorization': `Bearer ${apiToken}`
                    } : {}),
                },
            });

            if (!res.ok) throw new Error(`HTTP ${res.status}`);

            const json = await res.json();
            const data = json.data ?? json;

            const payload = {
                title: (data.title || data.name || '').toString(),
                desc: (data.description || '').toString(),
            };

            taskDetailCache[taskId] = payload;
            return payload;
        }

        $doc.off('click.taskLink', '.task-link').on('click.taskLink', '.task-link', async function(e) {
            e.preventDefault();

            const $link = $(this);
            if ($link.hasClass('disabled')) return;

            const taskNo = String($link.data('task-no') || '').trim();
            const taskId = String($link.data('task-id') || '').trim();

            const fallbackTitle = taskNo ? `Tugas ${taskNo}` : 'Detail Tugas';
            const initialTitle = String($link.data('task-title') || '').trim() || fallbackTitle;
            const initialDesc = String($link.data('task-desc') || '').trim();

            $modalTitle.text(initialTitle);
            $modalDesc.html(renderDesc(initialDesc));
            openModal();

            if (!taskId) return;

            $modalDesc.append('<div class="text-muted mt-2">Memuat versi lengkap...</div>');

            try {
                const detail = await fetchTaskDetail(taskId);
                if (detail) {
                    if (detail.title) $modalTitle.text(detail.title);
                    $modalDesc.html(renderDesc(detail.desc));
                } else {
                    if (!initialDesc) $modalDesc.html(
                        '<span class="text-muted">Deskripsi belum tersedia.</span>');
                }
            } catch (err) {
                console.warn('Fetch task detail gagal:', err);
                if (!initialDesc) $modalDesc.html(
                    '<span class="text-muted">Deskripsi belum tersedia.</span>');
            }
        });

        // =========================
        // EXPORT GUARD (allow all / ganjil / genap)
        // =========================
        function updateExportGuard() {
            const classroom = ($btnExport.attr('data-classroom') || '').trim();
            const subject = ($btnExport.attr('data-subject') || '').trim();

            const exportAllowed = exportHasData && classroom && subject;

            if (!exportAllowed) {
                $btnExport.addClass('disabled')
                    .attr('aria-disabled', 'true')
                    .css('pointer-events', 'none');
                return;
            }

            $btnExport.removeClass('disabled')
                .attr('aria-disabled', 'false')
                .css('pointer-events', '');
        }

        updateExportGuard();

        // =========================
        // EXPORT (with current filters)
        // =========================
        function setExportLoading(isLoading) {
            const $text = $btnExport.find('.btn-text');

            if (isLoading) {
                $btnExport.addClass('disabled').attr('aria-disabled', 'true').css('pointer-events', 'none');
                $text.text('Exporting...');
                return;
            }

            updateExportGuard();
            $text.text('Export Excel');
        }

        $doc.off('click.export', '#btnExportExcel').on('click.export', '#btnExportExcel', function(e) {
            e.preventDefault();

            if ($btnExport.hasClass('disabled') || $btnExport.attr('aria-disabled') === 'true') {
                toast('warning', 'Data belum siap untuk export.');
                return;
            }

            const classroom = ($btnExport.attr('data-classroom') || '').trim();
            const subject = ($btnExport.attr('data-subject') || '').trim();

            if (!classroom || !subject) {
                toast('error', 'classroom / subject kosong.');
                return;
            }

            const fd = new FormData($form[0]);
            const params = new URLSearchParams();

            for (const [key, val] of fd.entries()) {
                const v = (val ?? '').toString().trim();
                if (!v) continue;
                params.set(key, v);
            }

            const baseApi = @json(rtrim(config('app.api_url') ?: url('/'), '/'));
            let url = `${baseApi}/api/teacher/task-recap-export/${classroom}/${subject}`;

            const qs = params.toString();
            if (qs) url += `?${qs}`;

            setExportLoading(true);

            $.ajax({
                url,
                method: 'GET',
                xhrFields: {
                    responseType: 'blob'
                },
                beforeSend: function(xhr) {
                    if (apiToken) xhr.setRequestHeader('Authorization',
                        `Bearer ${apiToken}`);
                    xhr.setRequestHeader('Accept',
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                    );
                },
                success: function(blob, status, xhr) {
                    const ct = String(xhr.getResponseHeader('Content-Type') || '')
                        .toLowerCase();
                    if (ct.includes('text/html') || ct.includes('application/json')) {
                        toast('error',
                            'Gagal export. Server tidak mengembalikan file excel.');
                        return;
                    }

                    const disposition = xhr.getResponseHeader('Content-Disposition') || '';
                    const match = disposition.match(
                        /filename\*?=(?:UTF-8'')?["']?([^"';]+)["']?/i);

                    const sem = ($semester.val() || '').trim();
                    const semLabel = sem === 'ganjil' ? '_Semester_Ganjil' : sem ===
                        'genap' ? '_Semester_Genap' : '';

                    const filename = match ?
                        decodeURIComponent(match[1]).replace('.xlsx', `${semLabel}.xlsx`) :
                        `${classroom}_${subject}${semLabel}.xlsx`;

                    const blobUrl = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = blobUrl;
                    a.download = filename;
                    document.body.appendChild(a);
                    a.click();
                    a.remove();
                    window.URL.revokeObjectURL(blobUrl);

                    toast('success', 'Export berhasil.');
                },
                error: function(xhr) {
                    const blob = xhr.response;
                    if (blob instanceof Blob) {
                        const reader = new FileReader();
                        reader.onload = function() {
                            try {
                                const json = JSON.parse(reader.result);
                                const msg = json?.meta?.message || json?.message ||
                                    `Gagal export (${xhr.status}).`;
                                toast('error', msg);
                            } catch (e) {
                                toast('error', `Gagal export (${xhr.status}).`);
                            }
                        };
                        reader.readAsText(blob);
                    } else {
                        try {
                            const json = JSON.parse(xhr.responseText);
                            const msg = json?.meta?.message || json?.message ||
                                `Gagal export (${xhr.status}).`;
                            toast('error', msg);
                        } catch (e) {
                            toast('error', `Gagal export (${xhr.status}).`);
                        }
                    }
                },
                complete: function() {
                    setExportLoading(false);
                }
            });
        });

        // =========================
        // REORDER COLUMNS BY SEMESTER
        // =========================
        function reorderColumnsBySemester() {
            const $table = $('.rekap-table__table');
            if (!$table.length) return;

            const $r1 = $table.find('thead tr.rekap-head-semester');
            const $r2 = $table.find('thead tr.rekap-head-topic');
            const $r3 = $table.find('thead tr.rekap-head-task');

            if (!$r1.length || !$r2.length || !$r3.length) return;

            const $r1Cells = $r1.children('th');
            const $r2Cells = $r2.children('th');

            const $semThs = $r1Cells.slice(2);
            const $topicThs = $r2Cells;

            if (!$semThs.length || !$topicThs.length) return;

            const $taskThsAll = $r3.children('th');

            let taskPtr = 0;
            const blocks = [];

            $semThs.each(function(i) {
                const $semTh = $(this);
                const span = parseInt($semTh.attr('colspan') || '1', 10) || 1;

                const semesterText = ($semTh.text() || '').trim().toLowerCase();

                const $topicTh = $topicThs.eq(i);
                const $taskSlice = $taskThsAll.slice(taskPtr, taskPtr + span);
                taskPtr += span;

                blocks.push({
                    idx: i,
                    span,
                    semesterText,
                    $semTh,
                    $topicTh,
                    $taskSlice
                });
            });

            const weight = (t) => {
                if (t.includes('ganjil')) return 1;
                if (t.includes('genap')) return 2;
                return 99;
            };

            blocks.sort((a, b) => {
                const wa = weight(a.semesterText);
                const wb = weight(b.semesterText);
                if (wa !== wb) return wa - wb;
                return a.idx - b.idx;
            });

            const $fixedR1 = $r1Cells.slice(0, 2).detach();
            $r1.empty().append($fixedR1);
            blocks.forEach(b => $r1.append(b.$semTh.detach()));

            const $allR2 = $r2.children('th').detach();
            $r2.empty();
            blocks.forEach(b => $r2.append(b.$topicTh.detach()));

            const $allR3 = $r3.children('th').detach();
            $r3.empty();
            blocks.forEach(b => {
                b.$taskSlice.each(function() {
                    $r3.append($(this).detach());
                });
            });

            $table.find('tbody tr').each(function() {
                const $tr = $(this);
                const $cells = $tr.children('td');

                if ($cells.length <= 2) return;

                const $fixed = $cells.slice(0, 2).detach();
                let ptr = 2;

                const cellSlices = blocks.map((b) => {
                    const $slice = $cells.slice(ptr, ptr + b.span).detach();
                    ptr += b.span;
                    return $slice;
                });

                const byOriginalIdx = {};
                blocks.forEach((b, newPos) => {});

                $tr.empty().append($fixed);

                blocks.forEach(b => {
                    const $slice = cellSlices[b.idx];
                    $slice.each(function() {
                        $tr.append($(this));
                    });
                });
            });
        }

        reorderColumnsBySemester();

        $semester.on('change', function() {
            reorderColumnsBySemester();
        });

        // =========================
        // CUSTOM DROPDOWN SEMESTER
        // =========================
        const dropdown = $('#semester-dropdown');
        const selected = dropdown.find('.dropdown-selected');
        const options = dropdown.find('.dropdown-options');
        const optionItem = dropdown.find('.dropdown-option');
        const select = dropdown.find('select');

        selected.on('click', function() {
            selected.toggleClass('active');
            options.toggleClass('show');
        });

        optionItem.on('click', function() {
            const value = $(this).data('value');
            const text = $(this).text().trim();

            dropdown.find('.selected-text').text(text);

            select.val(value).trigger('change');

            // Close dropdown
            selected.removeClass('active');
            options.removeClass('show');
        });

        $doc.on('click', function(e) {
            if (!dropdown.is(e.target) && dropdown.has(e.target).length === 0) {
                selected.removeClass('active');
                options.removeClass('show');
            }
        });

    });
</script>
