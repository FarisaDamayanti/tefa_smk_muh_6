<script>
    $(document).ready(function() {
        const STORAGE_KEY = 'graduation_status';
        const ORIGINAL_STORAGE_KEY = 'graduation_original_status';

        const totalStudents = @json($graduationDetails['students']->total() ?? 0);
        const originalGraduationData = @json($graduationDetails['original_graduation_data']);

        // =========================
        // LOCAL STORAGE GETTERS
        // =========================
        function getGraduationData() {
            return JSON.parse(localStorage.getItem(STORAGE_KEY)) || {};
        }

        function getOriginalGraduationData() {
            return JSON.parse(localStorage.getItem(ORIGINAL_STORAGE_KEY)) || {};
        }


        // =========================
        // LOCAL STORAGE SETTERS
        // =========================
        function saveGraduationData(data) {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(data));
            toggleEditedAlert();
        }

        function saveOriginalGraduationData(data) {
            localStorage.setItem(ORIGINAL_STORAGE_KEY, JSON.stringify(data));
        }


        // =========================
        // CHECK CHANGES
        // =========================
        function hasUnsavedChanges() {
            const current = getGraduationData();
            const original = getOriginalGraduationData();

            const allIds = new Set([
                ...Object.keys(current),
                ...Object.keys(original)
            ]);

            for (let id of allIds) {
                if ((current[id] ?? null) !== (original[id] ?? null)) {
                    return true;
                }
            }

            return false;
        }


        // =========================
        // TOGGLE EDITED ALERT
        // =========================
        function toggleEditedAlert() {
            $('#alert-edited').toggleClass('d-none', !hasUnsavedChanges());
        }


        // =========================
        // RESET TO ORIGINAL DATA
        // =========================
        function resetToOriginal() {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(originalGraduationData));
            localStorage.setItem(ORIGINAL_STORAGE_KEY, JSON.stringify(originalGraduationData));

            $('.graduation-radio').each(function() {
                const studentId = $(this).data('student-id');
                const value = $(this).val();
                const originalValue = originalGraduationData[studentId] ?? null;

                $(this).prop('checked', originalValue === value);
            });

            checkAllStatus();
            toggleEditedAlert();
        }


        // =========================
        // LOAD INITIAL STATE
        // =========================
        function loadGraduationStatus() {
            let graduationData = getGraduationData();
            let originalStorageData = getOriginalGraduationData();

            let dbChanged = false;

            Object.keys(originalGraduationData).forEach(id => {
                if (originalStorageData[id] !== originalGraduationData[id]) {
                    dbChanged = true;
                }
            });

            if (dbChanged && Object.keys(originalStorageData).length > 0) {
                resetToOriginal();
                return;
            }

            if (Object.keys(originalStorageData).length === 0) {
                originalStorageData = {
                    ...originalGraduationData
                };
                graduationData = {
                    ...originalGraduationData
                };

                saveOriginalGraduationData(originalStorageData);
                saveGraduationData(graduationData);
            }

            $('.graduation-radio').each(function() {
                const studentId = $(this).data('student-id');
                const value = $(this).val();

                if (graduationData[studentId] === value) {
                    $(this).prop('checked', true);
                }
            });

            checkAllStatus();
            toggleEditedAlert();
        }


        // =========================
        // CHECK ALL STATUS
        // =========================
        function checkAllStatus() {
            let allPassed = true;
            let count = 0;

            $('.graduation-radio[value="passed"]:visible').each(function() {
                count++;
                if (!$(this).is(':checked')) {
                    allPassed = false;
                    return false;
                }
            });

            $('#checkAllGraduated').prop('checked', count > 0 && allPassed);
        }


        // =========================
        // RADIO CHANGE EVENT
        // =========================
        $('.graduation-radio').on('change', function() {
            let data = getGraduationData();

            data[$(this).data('student-id')] = $(this).val();

            saveGraduationData(data);
            checkAllStatus();
        });


        // =========================
        // SELECT ALL
        // =========================
        $('#checkAllGraduated').on('change', function() {
            let data = getGraduationData();
            let isChecked = $(this).is(':checked');

            $('.graduation-radio').each(function() {
                if ($(this).val() === (isChecked ? 'passed' : 'not_passed')) {
                    const id = $(this).data('student-id');
                    $(this).prop('checked', true);
                    data[id] = $(this).val();
                }
            });

            saveGraduationData(data);
        });


        // =========================
        // VALIDATION
        // =========================
        function validateGraduationData(graduates) {
            if (!Array.isArray(graduates)) {
                toastr.error('Format data tidak valid.');
                return false;
            }

            if (graduates.length !== totalStudents) {
                toastr.error('Semua siswa wajib memiliki status kelulusan.');
                return false;
            }

            for (let i = 0; i < graduates.length; i++) {
                const g = graduates[i];

                if (!g.student_id) {
                    toastr.error(`ID siswa wajib diisi pada baris ke-${i + 1}`);
                    return false;
                }

                if (g.status === null) {
                    toastr.error(`Terdapat siswa yang status kelulusannya belum ditentukan`);
                    return false;
                }

                if (!['passed', 'not_passed'].includes(g.status)) {
                    toastr.error(`Status tidak valid pada baris ke-${i + 1}`);
                    return false;
                }
            }

            return true;
        }


        // =========================
        // FORM SUBMIT
        // =========================
        $('#graduationForm').on('submit', function(e) {
            e.preventDefault();

            const buttonSubmit = $('#btn-submit-graduationDetail');

            setLoading(buttonSubmit);

            let data = getGraduationData();
            let graduates = [];

            $.each(data, function(id, status) {
                graduates.push({
                    student_id: id,
                    status: status
                });
            });

            if (!validateGraduationData(graduates)) {
                removeLoading(buttonSubmit, 'Simpan');
                return;
            }

            $('#graduatesInputContainer').remove();

            let html = '<div id="graduatesInputContainer">';

            graduates.forEach((g, i) => {
                html += `
                <input type="hidden" name="graduates[${i}][student_id]" value="${g.student_id}">
                <input type="hidden" name="graduates[${i}][status]" value="${g.status}">
            `;
            });

            html += '</div>';

            $('#graduationForm').append(html);
            this.submit();
        });


        // =========================
        // BUTTON
        // =========================
        function setLoading(element) {
            element.prop('disabled', true)
                .html('<div class="spinner-border spinner-border-sm me-2" role="status"></div>Memuat...');
        }

        function removeLoading(element, originalHtml) {
            element.prop('disabled', false)
                .html(originalHtml);
        }


        // =========================
        // INIT
        // =========================
        loadGraduationStatus();

        @if (session('success'))
            resetToOriginal();
        @endif
    });
</script>
