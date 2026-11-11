<script>
  window.togglePw = function (id, btn) {
    const input = document.getElementById(id);
    if (!input || !btn) return;
    const willShow = input.type === 'password';
    input.type = willShow ? 'text' : 'password';
    btn.classList.toggle('is-showing', willShow);
  };

  $(function () {
    const updateProfileUrl   = @json(route('api.student.profile.update'));
    const deleteImageUrl     = @json(route('api.student.profile.delete-image'));
    const updatePasswordUrl  = @json(route('api.update.password'));

    const apiToken           = @json(session('api_bearer') ?? '');
    const csrfToken          = @json(csrf_token());
    const defaultSrc         = @json(asset('assets/images/default-user.jpeg'));

    // =========================
    // VALIDATION HELPERS
    // =========================
    function clearValidation($form) {
      if (!$form || !$form.length) return;
      $form.find('.is-invalid').removeClass('is-invalid');
      $form.find('.invalid-feedback').text('');
    }

    function getFeedbackEl(input) {
      const $input = $(input);
      const $wrap = $input.closest('.col-12, .mb-3, .mb-4, .form-group');
      const $target = ($wrap.length ? $wrap : $input.parent());
      return $target.find('.invalid-feedback').first();
    }

    function setInvalid(input, message) {
      if (!input) return;
      $(input).addClass('is-invalid');
      const $fb = getFeedbackEl(input);
      if ($fb.length) $fb.text(message || 'Wajib diisi.');
    }

    function validateRequired($form) {
      let ok = true;
      $form.find('[required]').each(function () {
        const val = $.trim($(this).val() ?? '');
        if (!val) {
          ok = false;
          setInvalid(this, 'Wajib diisi.');
        }
      });
      return ok;
    }

    function applyServerErrors($form, errors) {
      if (!$form || !$form.length || !errors) return;

      Object.keys(errors).forEach(field => {
        const msgArr = errors[field];
        const msg = Array.isArray(msgArr) ? msgArr[0] : (msgArr || 'Input tidak valid.');

        const $input =
          $form.find(`[name="${CSS.escape(field)}"]`).first().length
            ? $form.find(`[name="${CSS.escape(field)}"]`).first()
            : $form.find(`[name="${CSS.escape(field)}[]"]`).first();

        if ($input.length) setInvalid($input[0], msg);
      });

      const firstKey = Object.keys(errors)[0];
      const $first =
        $form.find(`[name="${CSS.escape(firstKey)}"]`).first().length
          ? $form.find(`[name="${CSS.escape(firstKey)}"]`).first()
          : $form.find(`[name="${CSS.escape(firstKey)}[]"]`).first();

      if ($first.length) $first.trigger('focus');
    }

    // =========================
    // AUTH AJAX (GENERAL)
    // =========================
    function ajaxAuthedRequest({
      url,
      method = 'POST',
      data,
      processData = true,
      contentType = 'application/x-www-form-urlencoded; charset=UTF-8',
      onSuccess,
      onFail,
      onFinally
    }) {
      if (!apiToken) {
        toastr.error('api_bearer kosong. Endpoint butuh Bearer token.');
        return;
      }

      $.ajax({
        url,
        method,
        data,
        processData,
        contentType,
        headers: {
          'Accept': 'application/json',
          'Authorization': `Bearer ${apiToken}`,
          'X-CSRF-TOKEN': csrfToken
        }
      })
      .done(function (res) {
        if (typeof onSuccess === 'function') onSuccess(res);
      })
      .fail(function (xhr) {
        const status = xhr.status;
        const data = xhr.responseJSON || {};
        if (typeof onFail === 'function') onFail(xhr, data);

        if (!onFail) {
          if (status === 401) return toastr.error('401 Unauthenticated. Bearer token invalid / expired.');
          toastr.error(data?.message ?? `Request gagal (HTTP ${status})`);
        }
      })
      .always(function () {
        if (typeof onFinally === 'function') onFinally();
      });
    }

    function ajaxAuthForm(options) {
      const { $form, url, $btn, methodOverride = 'PUT', beforeValidate, onSuccess, onFinally } = options;
      if (!$form?.length) return;

      $form.on('submit', function (e) {
        e.preventDefault();

        clearValidation($form);

        if (typeof beforeValidate === 'function') {
          const ok = beforeValidate($form);
          if (ok === false) return;
        }

        if (!validateRequired($form)) return;

        if (!apiToken) {
          toastr.error('api_bearer kosong. Endpoint butuh Bearer token.');
          return;
        }

        const fd = new FormData(this);

        if (!String(fd.get('address') ?? '').trim()) {
          fd.delete('address');
        }

        if (methodOverride) fd.append('_method', methodOverride);

        $btn?.prop('disabled', true);

        $.ajax({
          url,
          method: 'POST',
          data: fd,
          processData: false,
          contentType: false,
          headers: {
            'Accept': 'application/json',
            'Authorization': `Bearer ${apiToken}`,
            'X-CSRF-TOKEN': csrfToken
          }
        })
        .done(function (data) {
          if (typeof onSuccess === 'function') onSuccess(data, $form);
        })
        .fail(function (xhr) {
          const status = xhr.status;
          const data = xhr.responseJSON || {};

          if (status === 422 || status === 400) {
            const errors = data?.errors || data?.data || data?.meta?.errors || null;
            if (errors) return applyServerErrors($form, errors);
            if (data?.message) return toastr.error(data.message);
          }

          if (status === 401) return toastr.error('401 Unauthenticated. Bearer token invalid / expired.');
          toastr.error(data?.message ?? `Request gagal (HTTP ${status})`);
        })
        .always(function () {
          $btn?.prop('disabled', false);
          if (typeof onFinally === 'function') onFinally();
        });
      });
    }

    const $formProfile     = $('#formProfile');
    const $btnPickImage    = $('#btnPickImage');
    const $btnResetImage   = $('#btnResetImage');
    const $imageInput      = $('#imageInput');
    const $previewImage    = $('#previewImage');
    const $photoOverlay    = $('#photoOverlay');
    const $btnSave         = $('#btnSave');

    let lastObjectUrl = null;
    let lastFinalSrc  = null;

    function showOverlay(show) {
      if (!$photoOverlay.length) return;
      $photoOverlay.css('display', show ? 'flex' : 'none');
    }

    function isDefaultImage(src) {
      if (!src) return true;
      return String(src).includes('default-user.jpeg');
    }

    function getHeaderProfileImgs() {
      const imgs = [];
      const byId1 = document.getElementById('navbarProfileImage');
      const byId2 = document.getElementById('dropdownProfileImage');
      if (byId1) imgs.push(byId1);
      if (byId2) imgs.push(byId2);

      $('a#profileDropdown img.rounded-circle').each(function () { imgs.push(this); });
      $('.nav-item.dropdown img.rounded-circle').each(function () { imgs.push(this); });

      return [...new Set(imgs)];
    }

    function setAllProfileImages(src) {
      if (!src) return;

      const isBlob = String(src).startsWith('blob:');
      const finalSrc = isBlob ? src : `${src}${String(src).includes('?') ? '&' : '?'}v=${Date.now()}`;

      lastFinalSrc = finalSrc;

      if ($previewImage.length) $previewImage.attr('src', finalSrc);

      getHeaderProfileImgs().forEach(img => {
        img.src = finalSrc;
      });
    }

    const mo = new MutationObserver(() => {
      if (lastFinalSrc) {
        getHeaderProfileImgs().forEach(img => { img.src = lastFinalSrc; });
      }
    });
    mo.observe(document.body, { childList: true, subtree: true });

    const initialSrc = $previewImage.attr('src');
    if (initialSrc) showOverlay(isDefaultImage(initialSrc));

    $btnPickImage.on('click', function () {
      $imageInput.trigger('click');
    });

    $imageInput.on('change', function () {
      const file = this.files && this.files[0];
      if (!file) return;

      if (!file.type.startsWith('image/')) {
        toastr.error('File harus berupa gambar.');
        $(this).val('');
        return;
      }

      if (lastObjectUrl) URL.revokeObjectURL(lastObjectUrl);
      lastObjectUrl = URL.createObjectURL(file);

      setAllProfileImages(lastObjectUrl);
      showOverlay(false);
    });

    const modalEl = document.getElementById('confirmDeleteProfileImageModal');
    const bsModal = (modalEl && window.bootstrap && bootstrap.Modal)
      ? bootstrap.Modal.getOrCreateInstance(modalEl)
      : null;

    function openDeleteModal() {
      if (bsModal) return bsModal.show();
      if (typeof $('#confirmDeleteProfileImageModal').modal === 'function') return $('#confirmDeleteProfileImageModal').modal('show');
      if (confirm('Apakah anda yakin ingin menghapus foto profil ini?')) {
        $('#btnConfirmDeleteProfileImage').trigger('click');
      }
    }

    function closeDeleteModal() {
      if (bsModal) return bsModal.hide();
      if (typeof $('#confirmDeleteProfileImageModal').modal === 'function') return $('#confirmDeleteProfileImageModal').modal('hide');
    }

    // klik tombol Reset -> buka modal
    $btnResetImage.on('click', function () {
      openDeleteModal();
    });

    $('#btnConfirmDeleteProfileImage').on('click', function () {
      const rollbackSrc = lastFinalSrc || initialSrc || defaultSrc;

      const $confirmBtn = $(this);
      $confirmBtn.prop('disabled', true);

      $imageInput.val('');
      if (lastObjectUrl) {
        URL.revokeObjectURL(lastObjectUrl);
        lastObjectUrl = null;
      }
      setAllProfileImages(defaultSrc);
      showOverlay(true);

      ajaxAuthedRequest({
        url: deleteImageUrl,
        method: 'POST',
        data: { _method: 'PUT' },
        onSuccess: function (res) {
          toastr.success(res?.message ?? 'Foto profil berhasil dihapus');

          const serverImage =
            res?.data?.image_url || res?.data?.image || res?.image_url || res?.image || null;

          setAllProfileImages(serverImage || defaultSrc);
          showOverlay(true);

          closeDeleteModal();
          window.location.reload();
        },
        onFail: function (xhr, data) {
          const status = xhr.status;

          if (status === 401) {
            toastr.error('401 Unauthenticated. Bearer token invalid / expired.');
          } else {
            toastr.error(data?.message ?? `Gagal hapus foto (HTTP ${status})`);
          }

          // rollback UI
          setAllProfileImages(rollbackSrc);
          showOverlay(isDefaultImage(rollbackSrc));

          closeDeleteModal();
        },
        onFinally: function () {
          $confirmBtn.prop('disabled', false);
        }
      });
    });

    // =========================
    // PROFILE SUBMIT
    // =========================
    ajaxAuthForm({
      $form: $formProfile,
      url: updateProfileUrl,
      $btn: $btnSave,
      methodOverride: 'PUT',
      onSuccess: function (data) {
        toastr.success(data?.message ?? 'Berhasil update profile');

        const serverImage =
          data?.data?.image_url ||
          data?.data?.image ||
          data?.image_url ||
          data?.image ||
          null;

        if (serverImage) {
          setAllProfileImages(serverImage);
          showOverlay(isDefaultImage(serverImage));
        } else if (lastObjectUrl) {
          setAllProfileImages(lastObjectUrl);
          showOverlay(false);
        }

        window.location.reload();
      }
    });

    // =========================
    // PASSWORD SUBMIT
    // =========================
    const $formPassword     = $('#formUpdatePassword');
    const $btnSavePassword  = $('#btnSavePassword');

    ajaxAuthForm({
      $form: $formPassword,
      url: updatePasswordUrl,
      $btn: $btnSavePassword,
      methodOverride: 'PUT',
      beforeValidate: function ($form) {
        const $pw  = $form.find('[name="password"]').first();
        const $pwc = $form.find('[name="password_confirmation"]').first();

        if ($pw.length && $pwc.length && $pw.val() !== $pwc.val()) {
          setInvalid($pwc[0], 'Konfirmasi kata sandi tidak sama.');
          $pwc.trigger('focus');
          return false;
        }
        return true;
      },
      onSuccess: function (data, $form) {
        toastr.success(data?.message ?? 'Berhasil update password');
        $form[0].reset();
        clearValidation($form);
      }
    });

  });
</script>
