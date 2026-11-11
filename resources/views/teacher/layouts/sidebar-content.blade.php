      <nav class="sidebar-nav scroll-sidebar d-flex flex-column" data-simplebar>

          {{-- role guru --}}
          @role('teacher')
              <ul id="sidebarnav">
                  <!-- ============================= -->
                  <!-- Home -->
                  <!-- ============================= -->
                  <li class="nav-small-cap">
                      <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                      <span class="hide-menu">Home</span>
                  </li>
                  <!-- =================== -->
                  <!-- Dashboard -->
                  <!-- =================== -->
                  <li class="sidebar-item">
                      <a class="sidebar-link {{ request()->routeIs('teacher.dashboard') ? 'active' : '' }}"
                          href="{{ route('teacher.dashboard') }}" aria-expanded="false">
                          <div class="sidebar-link-inner">
                              <span>
                                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                      fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                      stroke-linejoin="round"
                                      class="icon icon-tabler icons-tabler-outline icon-tabler-aperture">
                                      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                      <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                      <path d="M3.6 15h10.55" />
                                      <path d="M6.551 4.938l3.26 10.034" />
                                      <path d="M17.032 4.636l-8.535 6.201" />
                                      <path d="M20.559 14.51l-8.535 -6.201" />
                                      <path d="M12.257 20.916l3.261 -10.034" />
                                  </svg>
                              </span>

                              <span class="hide-menu">Beranda</span>
                          </div>
                      </a>
                  </li>
                  <li class="sidebar-item">
                      <a class="sidebar-link" href="/teacher/attendance/history" aria-expanded="false">
                          <div class="sidebar-link-inner">
                              <span>
                                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                      <g fill="none" stroke="currentColor" stroke-width="1.5">
                                          <circle cx="9" cy="9" r="2" />
                                          <path d="M13 15c0 1.105 0 2-4 2s-4-.895-4-2s1.79-2 4-2s4 .895 4 2Z" />
                                          <path
                                              d="M2 12c0-3.771 0-5.657 1.172-6.828S6.229 4 10 4h4c3.771 0 5.657 0 6.828 1.172S22 8.229 22 12s0 5.657-1.172 6.828S17.771 20 14 20h-4c-3.771 0-5.657 0-6.828-1.172S2 15.771 2 12Z" />
                                          <path stroke-linecap="round" d="M19 12h-4m4-3h-5m5 6h-3" />
                                      </g>
                                  </svg>
                              </span>
                              <span class="hide-menu">Absensi</span>
                          </div>
                      </a>
                  </li>
                  <li class="sidebar-item">
                      <a class="sidebar-link {{ request()->routeIs('teacher.journals.index') || request()->routeIs('teacher.journals.create') || request()->routeIs('teacher.journals.show') || request()->routeIs('teacher.journals.edit') ? 'active' : '' }}"
                          href="{{ route('teacher.journals.index') }}" aria-expanded="false">
                          <div class="sidebar-link-inner">
                              <span>
                                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 16 16">
                                      <g fill="currentColor">
                                          <path fill-rule="evenodd"
                                              d="M6 8V1h1v6.117L8.743 6.07a.5.5 0 0 1 .514 0L11 7.117V1h1v7a.5.5 0 0 1-.757.429L9 7.083L6.757 8.43A.5.5 0 0 1 6 8" />
                                          <path
                                              d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2" />
                                          <path
                                              d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1z" />
                                      </g>
                                  </svg>
                              </span>
                              <span class="hide-menu">Jurnal</span>
                          </div>
                      </a>
                  </li>
                  <li class="sidebar-item">
                      <a class="sidebar-link {{ request()->routeIs('teacher.e-learning.*') || request()->routeIs('teacher.evaluation.*') ? 'active' : '' }}"
                          href="javascript:void(0)" data-bs-toggle="collapse" data-bs-target="#elearningCollapse"
                          aria-expanded="{{ request()->routeIs('teacher.e-learning.*') || request()->routeIs('teacher.evaluation.*') ? 'true' : 'false' }}"
                          aria-controls="elearningCollapse">

                          <div class="sidebar-link-inner">
                              <span>
                                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 16 16">
                                      <g fill="currentColor">
                                          <path fill-rule="evenodd"
                                              d="M6 8V1h1v6.117L8.743 6.07a.5.5 0 0 1 .514 0L11 7.117V1h1v7a.5.5 0 0 1-.757.429L9 7.083L6.757 8.43A.5.5 0 0 1 6 8" />
                                          <path
                                              d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2" />
                                          <path
                                              d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1z" />
                                      </g>
                                  </svg>
                              </span>

                              <span class="hide-menu">E-Learning</span>

                              <span class="ms-auto">
                                  <i class="ti ti-chevron-down"></i>
                              </span>
                          </div>
                      </a>
                  </li>
                  <div class="collapse {{ request()->routeIs('teacher.e-learning.*') || request()->routeIs('teacher.evaluation.*') ? 'show' : '' }}"
                      id="elearningCollapse">
                      <ul class="sidebar-submenu">
                          <!-- Menu 1: Kelas -->
                          <li class="sidebar-item">
                              <a class="sidebar-link {{ request()->routeIs('teacher.e-learning.*') ? 'active' : '' }}"
                                  href="{{ route('teacher.e-learning.index') }}">
                                  <div class="sidebar-link-inner">
                                      <span class="me-2">
                                          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                              viewBox="0 0 20 20">
                                              <path fill="currentColor"
                                                  d="M8.5 2a1.5 1.5 0 0 0-1.415 1H5.5A1.5 1.5 0 0 0 4 4.5v12A1.5 1.5 0 0 0 5.5 18h2.504a2 2 0 0 1 .054-.347l.375-1.498a3.2 3.2 0 0 1 .84-1.485l4.83-4.83A2.86 2.86 0 0 1 16 9.004V4.5A1.5 1.5 0 0 0 14.5 3h-1.585A1.5 1.5 0 0 0 11.5 2zM8 3.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5m1.98 11.877l4.83-4.83a1.87 1.87 0 1 1 2.644 2.646l-4.83 4.829a2.2 2.2 0 0 1-1.02.578l-1.498.374a.89.89 0 0 1-1.079-1.078l.375-1.498a2.2 2.2 0 0 1 .578-1.02" />
                                          </svg>
                                      </span>
                                      <span class="hide-menu">Kelas</span>
                                  </div>
                              </a>
                          </li>

                          <!-- Menu 2: Rekap Nilai -->
                          <li class="sidebar-item">
                              <a class="sidebar-link {{ request()->routeIs('teacher.evaluation.*') ? 'active' : '' }}"
                                  href="{{ route('teacher.evaluation.index') }}">
                                  <div class="sidebar-link-inner">
                                      <span class="me-2">
                                          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                              viewBox="0 0 48 48">
                                              <g fill="currentColor">
                                                  <path d="M20 18.6L17.75 24h4.5z" />
                                                  <path fill-rule="evenodd"
                                                      d="M38 15L28 4H14a4 4 0 0 0-4 4v32a4 4 0 0 0 4 4h20a4 4 0 0 0 4-4zm-18 0a1 1 0 0 1 .923.615l5 12a1 1 0 0 1-1.846.77L23.083 26h-6.166l-.994 2.385a1 1 0 0 1-1.846-.77l5-12A1 1 0 0 1 20 15m-5 17a1 1 0 1 0 0 2h18a1 1 0 1 0 0-2zm-1 5a1 1 0 0 1 1-1h12a1 1 0 1 1 0 2H15a1 1 0 0 1-1-1m17-15a1 1 0 1 0-2 0v2h-2a1 1 0 1 0 0 2h2v2a1 1 0 1 0 2 0v-2h2a1 1 0 1 0 0-2h-2zM28 7l7 8h-6a1 1 0 0 1-1-1z"
                                                      clip-rule="evenodd" />
                                                  <path fill-rule="evenodd"
                                                      d="M25.923 27.614v.001q.043.104.062.209a3 3 0 0 1-1.2-.8q-.086.018-.17.053a1 1 0 0 0-.538 1.306L23.083 26h-6.166l-.994 2.384a1 1 0 0 1-1.846-.769l.993-2.384l4.007-9.616a1 1 0 0 1 1.846 0l.923 2.214v.002l2.25 5.4v.001zm-.077-5.384l-3.077-7.384a3 3 0 0 0-5.538 0l-5 12A3 3 0 0 0 12 27.93V8a2 2 0 0 1 2-2h12.268c-.172.298-.268.64-.268 1v7a3 3 0 0 0 3 3h6a2 2 0 0 0 1-.268V25a3 3 0 0 0-3-3a3 3 0 1 0-6 0a3 3 0 0 0-1.154.23m.77 1.847A1 1 0 0 0 27 26h2v2a1 1 0 1 0 2 0v-2h2a1 1 0 1 0 0-2h-2v-2a1 1 0 1 0-2 0v2h-2a1 1 0 0 0-.385.077m.884 5.582q.12.18.264.341h-.527q.144-.162.263-.341m-5.27-.505c.134.319.315.602.533.846zm.533.846h-5.526c.218-.244.4-.527.532-.846L18.25 28h3.5l.48 1.154M13.34 30.5A3 3 0 0 1 12 28.067V33c0-1.043.533-1.962 1.34-2.499m-.576 4.5a3 3 0 0 1-.764-2V37c0-.768.29-1.468.764-1.999m19.472-5c.475-.53.764-1.232.764-2a3 3 0 0 0 3-3v8a3 3 0 0 0-3-3zm-2.407 6H33a3 3 0 0 0 3-3v7a2 2 0 0 1-2 2H14a2 2 0 0 1-2-2v-2.999A3 3 0 0 0 15 40h12a3 3 0 0 0 2.83-4m-2.826-2H33a1 1 0 1 0 0-2H15a1 1 0 1 0 0 2h12.003M38 15v25a4 4 0 0 1-4 4H14a4 4 0 0 1-4-4V8a4 4 0 0 1 4-4h14zM28 7l7 8h-6a1 1 0 0 1-1-1zm-5.75 17h-4.5L20 18.6zm1.827 4.385v-.002zM15 36a1 1 0 1 0 0 2h12a1 1 0 1 0 0-2z"
                                                      clip-rule="evenodd" />
                                              </g>
                                          </svg>
                                      </span>
                                      <span class="hide-menu">Rekap Nilai</span>
                                  </div>
                              </a>
                          </li>
                      </ul>
                  </div>
                  </li>
                  <li class="sidebar-item">
                      <a class="sidebar-link {{ request()->routeIs('teacher.e-learning.*') || request()->routeIs('teacher.evaluation.*') ? 'active' : '' }}"
                          href="javascript:void(0)" data-bs-toggle="collapse" data-bs-target="#examCollapse"
                          aria-expanded="{{ request()->routeIs('teacher.e-learning.*') || request()->routeIs('teacher.evaluation.*') ? 'true' : 'false' }}"
                          aria-controls="examCollapse">

                          <div class="sidebar-link-inner">
                              <span>
                                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                      viewBox="0 0 16 16">
                                      <g fill="currentColor">
                                          <path fill-rule="evenodd"
                                              d="M6 8V1h1v6.117L8.743 6.07a.5.5 0 0 1 .514 0L11 7.117V1h1v7a.5.5 0 0 1-.757.429L9 7.083L6.757 8.43A.5.5 0 0 1 6 8" />
                                          <path
                                              d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2" />
                                          <path
                                              d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1z" />
                                      </g>
                                  </svg>
                              </span>

                              <span class="hide-menu">Ujian</span>

                              <span class="ms-auto">
                                  <i class="ti ti-chevron-down"></i>
                              </span>
                          </div>
                      </a>
                  </li>
                  <div class="collapse {{ request()->routeIs('teacher.question-bank.*') || request()->routeIs('teacher.evaluation.*') ? 'show' : '' }}"
                      id="examCollapse">
                      <ul class="sidebar-submenu">
                          <!-- Menu 1: Kelas -->
                          <li class="sidebar-item">
                              <a class="sidebar-link {{ request()->routeIs('teacher.question-bank.index') ? 'active' : '' }}"
                                  href="{{ route('teacher.question-bank.index') }}">
                                  <div class="sidebar-link-inner">
                                      <span class="me-2">
                                          <svg width="15" height="20" viewBox="0 0 15 20" fill="none"
                                              xmlns="http://www.w3.org/2000/svg">
                                              <path
                                                  d="M0 2.5C0 1.12109 1.12109 0 2.5 0H8.33984C9.00391 0 9.64062 0.261719 10.1094 0.730469L14.2695 4.89453C14.7383 5.36328 15 6 15 6.66406V17.5C15 18.8789 13.8789 20 12.5 20H2.5C1.12109 20 0 18.8789 0 17.5V2.5ZM8.125 2.28516V5.9375C8.125 6.45703 8.54297 6.875 9.0625 6.875H12.7148L8.125 2.28516ZM6.83594 17.2266C7.20312 17.5938 7.79687 17.5938 8.16016 17.2266L10.6602 14.7266C11.0273 14.3594 11.0273 13.7656 10.6602 13.4023C10.293 13.0391 9.69922 13.0352 9.33594 13.4023L8.4375 14.3008V10.9375C8.4375 10.418 8.01953 10 7.5 10C6.98047 10 6.5625 10.418 6.5625 10.9375V14.3008L5.66406 13.4023C5.29688 13.0352 4.70312 13.0352 4.33984 13.4023C3.97656 13.7695 3.97266 14.3633 4.33984 14.7266L6.83984 17.2266H6.83594Z"
                                                  fill="#000" />
                                          </svg>

                                      </span>
                                      <span class="hide-menu">Bank Soal</span>
                                  </div>
                              </a>
                          </li>

                          <!-- Menu 2: Rekap Nilai -->
                          <li class="sidebar-item">
                              <a class="sidebar-link" href="{{ route('teacher.exam-monitoring.index') }}">
                                  <div class="sidebar-link-inner">
                                      <span class="me-2">
                                          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                              viewBox="0 0 48 48">
                                              <g fill="currentColor">
                                                  <path d="M20 18.6L17.75 24h4.5z" />
                                                  <path fill-rule="evenodd"
                                                      d="M38 15L28 4H14a4 4 0 0 0-4 4v32a4 4 0 0 0 4 4h20a4 4 0 0 0 4-4zm-18 0a1 1 0 0 1 .923.615l5 12a1 1 0 0 1-1.846.77L23.083 26h-6.166l-.994 2.385a1 1 0 0 1-1.846-.77l5-12A1 1 0 0 1 20 15m-5 17a1 1 0 1 0 0 2h18a1 1 0 1 0 0-2zm-1 5a1 1 0 0 1 1-1h12a1 1 0 1 1 0 2H15a1 1 0 0 1-1-1m17-15a1 1 0 1 0-2 0v2h-2a1 1 0 1 0 0 2h2v2a1 1 0 1 0 2 0v-2h2a1 1 0 1 0 0-2h-2zM28 7l7 8h-6a1 1 0 0 1-1-1z"
                                                      clip-rule="evenodd" />
                                                  <path fill-rule="evenodd"
                                                      d="M25.923 27.614v.001q.043.104.062.209a3 3 0 0 1-1.2-.8q-.086.018-.17.053a1 1 0 0 0-.538 1.306L23.083 26h-6.166l-.994 2.384a1 1 0 0 1-1.846-.769l.993-2.384l4.007-9.616a1 1 0 0 1 1.846 0l.923 2.214v.002l2.25 5.4v.001zm-.077-5.384l-3.077-7.384a3 3 0 0 0-5.538 0l-5 12A3 3 0 0 0 12 27.93V8a2 2 0 0 1 2-2h12.268c-.172.298-.268.64-.268 1v7a3 3 0 0 0 3 3h6a2 2 0 0 0 1-.268V25a3 3 0 0 0-3-3a3 3 0 1 0-6 0a3 3 0 0 0-1.154.23m.77 1.847A1 1 0 0 0 27 26h2v2a1 1 0 1 0 2 0v-2h2a1 1 0 1 0 0-2h-2v-2a1 1 0 1 0-2 0v2h-2a1 1 0 0 0-.385.077m.884 5.582q.12.18.264.341h-.527q.144-.162.263-.341m-5.27-.505c.134.319.315.602.533.846zm.533.846h-5.526c.218-.244.4-.527.532-.846L18.25 28h3.5l.48 1.154M13.34 30.5A3 3 0 0 1 12 28.067V33c0-1.043.533-1.962 1.34-2.499m-.576 4.5a3 3 0 0 1-.764-2V37c0-.768.29-1.468.764-1.999m19.472-5c.475-.53.764-1.232.764-2a3 3 0 0 0 3-3v8a3 3 0 0 0-3-3zm-2.407 6H33a3 3 0 0 0 3-3v7a2 2 0 0 1-2 2H14a2 2 0 0 1-2-2v-2.999A3 3 0 0 0 15 40h12a3 3 0 0 0 2.83-4m-2.826-2H33a1 1 0 1 0 0-2H15a1 1 0 1 0 0 2h12.003M38 15v25a4 4 0 0 1-4 4H14a4 4 0 0 1-4-4V8a4 4 0 0 1 4-4h14zM28 7l7 8h-6a1 1 0 0 1-1-1zm-5.75 17h-4.5L20 18.6zm1.827 4.385v-.002zM15 36a1 1 0 1 0 0 2h12a1 1 0 1 0 0-2z"
                                                      clip-rule="evenodd" />
                                              </g>
                                          </svg>
                                      </span>
                                      <span class="hide-menu">Rekap Nilai Ujian</span>
                                  </div>
                              </a>
                          </li>
                      </ul>
                  </div>
                  </li>


                  @if ($permission_feedback ?? false)
                      <li class="sidebar-item">
                          <a class="sidebar-link" href="/teacher/student-feedback" aria-expanded="false">
                              <div class="sidebar-link-inner">
                                  <span>
                                      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                          viewBox="0 0 20 20">
                                          <path fill="currentColor"
                                              d="M11.5 1A1.5 1.5 0 0 0 10 2.5v5A1.5 1.5 0 0 0 11.5 9h6A1.5 1.5 0 0 0 19 7.5v-5A1.5 1.5 0 0 0 17.5 1zm1 5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1 0-1M12 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5M4.6 3H9v1H4.6C3.704 4 3 4.713 3 5.566v6.71c0 .853.704 1.566 1.6 1.566h1.6V17h.003l.002-.001l4.276-3.157H15.4c.896 0 1.6-.713 1.6-1.566V10h.5q.257 0 .5-.05v2.326c0 1.418-1.164 2.566-2.6 2.566h-4.59l-4.011 2.961a1.01 1.01 0 0 1-1.4-.199a.98.98 0 0 1-.199-.59v-2.172h-.6c-1.436 0-2.6-1.149-2.6-2.566v-6.71C2 4.149 3.164 3 4.6 3" />
                                      </svg>
                                  </span>
                                  <span class="hide-menu">Tanggapan Siswa</span>
                              </div>
                          </a>
                      </li>
                  @endif

                  @if (App\Models\Classroom::where('employee_id', auth()->user()->employee->id)->exists())
                      <li class="sidebar-item">
                          <a class="sidebar-link {{ request()->routeIs('teacher.list-student-class.index') ? 'active' : '' }}"
                              href="{{ route('teacher.list-student-class.index') }}" aria-expanded="false">
                              <div class="sidebar-link-inner">
                                  <span>
                                      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                          viewBox="0 0 24 24">
                                          <path fill="none" stroke="currentColor" stroke-linecap="round"
                                              stroke-linejoin="round" stroke-width="1.5"
                                              d="M17.928 19.634h2.138a1.165 1.165 0 0 0 1.116-1.555a6.851 6.851 0 0 0-6.117-3.95m0-2.759a3.664 3.664 0 0 0 3.665-3.664a3.664 3.664 0 0 0-3.665-3.674m-1.04 16.795a1.908 1.908 0 0 0 1.537-3.035a8.026 8.026 0 0 0-6.222-3.196a8.026 8.026 0 0 0-6.222 3.197a1.909 1.909 0 0 0 1.536 3.034zM9.34 11.485a4.16 4.16 0 0 0 4.15-4.161a4.151 4.151 0 0 0-8.302 0a4.16 4.16 0 0 0 4.151 4.16" />
                                      </svg>
                                  </span>
                                  <span class="hide-menu">Daftar Siswa Dikelas</span>
                              </div>

                          </a>
                      </li>
                  @endif

                  {{-- <li class="sidebar-item ">
                    <a class="sidebar-link" href="javascript:void(0)" aria-expanded="false">
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 256 256">
                                <path fill="currentColor"
                                    d="M201.57 54.46a104 104 0 1 0 0 147.08a103.4 103.4 0 0 0 0-147.08M65.75 65.77a87.63 87.63 0 0 1 53.66-25.31A87.3 87.3 0 0 1 94 94.06a87.42 87.42 0 0 1-53.62 25.35a87.58 87.58 0 0 1 25.37-53.64m-25.42 69.71a103.3 103.3 0 0 0 65-30.11a103.24 103.24 0 0 0 30.13-65a87.78 87.78 0 0 1 80.18 80.14a104 104 0 0 0-95.16 95.1a87.78 87.78 0 0 1-80.18-80.14Zm149.92 54.75a87.7 87.7 0 0 1-53.66 25.31a88 88 0 0 1 79-78.95a87.58 87.58 0 0 1-25.34 53.64" />
                            </svg>
                        </span>
                        <span class="hide-menu">Ekstrakulikuler</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                        <span class="d-flex">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                <path fill="none" stroke="currentColor" stroke-linecap="round"
                                    stroke-linejoin="round" stroke-width="2"
                                    d="M2 6s1.5-2 5-2s5 2 5 2v14s-1.5-1-5-1s-5 1-5 1zm10 0s1.5-2 5-2s5 2 5 2v14s-1.5-1-5-1s-5 1-5 1z" />
                            </svg>
                        </span>
                        <span class="hide-menu">E - Learning</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level">
                        <li class="sidebar-item">
                            <a href="javascript:void(0)" class="sidebar-link">
                                <div class="round-16 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-circle"></i>
                                </div>
                                <span class="hide-menu">E - Learning</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="javascript:void(0)" class="sidebar-link">
                                <div class="round-16 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-circle"></i>
                                </div>
                                <span class="hide-menu">Kelas</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="javascript:void(0)" class="sidebar-link">
                                <div class="round-16 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-circle"></i>
                                </div>
                                <span class="hide-menu">Daftar Tugas</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                        <span class="d-flex">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="M13 14H9a1 1 0 0 0 0 2h4a1 1 0 0 0 0-2m4-10h-1.18A3 3 0 0 0 13 2h-2a3 3 0 0 0-2.82 2H7a3 3 0 0 0-3 3v12a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3m-7 1a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v1h-4Zm8 14a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h1v1a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V6h1a1 1 0 0 1 1 1Zm-3-9H9a1 1 0 0 0 0 2h6a1 1 0 0 0 0-2" />
                            </svg>
                        </span>
                        <span class="hide-menu">Rapor</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level">
                        <li class="sidebar-item">
                            <a href="javascript:void(0)" class="sidebar-link">
                                <div class="round-16 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-circle"></i>
                                </div>
                                <span class="hide-menu">Input Nilai</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="javascript:void(0)" class="sidebar-link">
                                <div class="round-16 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-circle"></i>
                                </div>
                                <span class="hide-menu">Deskripsi Karakter</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                        <span class="d-flex">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="M20.5 11H19V7a2 2 0 0 0-2-2h-4V3.5A2.5 2.5 0 0 0 10.5 1A2.5 2.5 0 0 0 8 3.5V5H4a2 2 0 0 0-2 2v3.8h1.5c1.5 0 2.7 1.2 2.7 2.7S5 16.2 3.5 16.2H2V20a2 2 0 0 0 2 2h3.8v-1.5c0-1.5 1.2-2.7 2.7-2.7s2.7 1.2 2.7 2.7V22H17a2 2 0 0 0 2-2v-4h1.5a2.5 2.5 0 0 0 2.5-2.5a2.5 2.5 0 0 0-2.5-2.5" />
                            </svg>
                        </span>
                        <span class="hide-menu">Ujian</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level">
                        <li class="sidebar-item">
                            <a href="javascript:void(0)" class="sidebar-link">
                                <div class="round-16 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-circle"></i>
                                </div>
                                <span class="hide-menu">Input Nilai</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="javascript:void(0)" class="sidebar-link">
                                <div class="round-16 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-circle"></i>
                                </div>
                                <span class="hide-menu">Deskripsi Karakter</span>
                            </a>
                        </li>
                    </ul>
                </li> --}}
              </ul>
          @endrole

          @role('staff')
              <ul id="sidebarnav">
                  <!-- ============================= -->
                  <!-- Home -->
                  <!-- ============================= -->
                  <li class="nav-small-cap">
                      <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                      <span class="hide-menu">Home</span>
                  </li>
                  <!-- =================== -->
                  <!-- Dashboard -->
                  <!-- =================== -->
                  <li class="sidebar-item">
                      <a class="sidebar-link" href="{{ route('employee.dashboard') }}" aria-expanded="false">
                          <div class="sidebar-link-inner">
                              <span>
                                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                      viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                      stroke-linecap="round" stroke-linejoin="round"
                                      class="icon icon-tabler icons-tabler-outline icon-tabler-aperture">
                                      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                      <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                      <path d="M3.6 15h10.55" />
                                      <path d="M6.551 4.938l3.26 10.034" />
                                      <path d="M17.032 4.636l-8.535 6.201" />
                                      <path d="M20.559 14.51l-8.535 -6.201" />
                                      <path d="M12.257 20.916l3.261 -10.034" />
                                  </svg>
                              </span>
                              <span class="hide-menu">Beranda</span>
                          </div>

                      </a>
                  </li>
                  <li class="nav-small-cap">
                      <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                      <span class="hide-menu">Buku Tamu</span>
                  </li>

                  <li class="sidebar-item ">
                      <a class="sidebar-link {{ Route::is('employee.guestbook.index') ? 'active' : '' }}"
                          href="{{ route('employee.guestbook.index') }}" aria-expanded="false">
                          <div class="sidebar-link-inner">
                              <span>
                                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                      viewBox="0 0 24 24">
                                      <path fill="currentColor"
                                          d="M3 2h16.005C20.107 2 21 2.898 21 3.99v16.02c0 1.099-.893 1.99-1.995 1.99H3zm4 2H5v16h2zm2 16h10V4H9zm2-4a3 3 0 1 1 6 0zm3-4a2 2 0 1 1 0-4a2 2 0 0 1 0 4m8-6h2v4h-2zm0 6h2v4h-2z" />
                                  </svg>
                              </span>
                              <span class="hide-menu">Buku Tamu</span>
                          </div>

                      </a>
                  </li>

                  <li class="nav-small-cap">
                      <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                      <span class="hide-menu">Jurnal</span>
                  </li>
                  <li class="sidebar-item ">
                      <a class="sidebar-link {{ request()->routeIs('employee.journal.index') || request()->routeIs('employee.journal.detail') ? 'active' : '' }}"
                          href="{{ route('employee.journal.index') }}" aria-expanded="false">\
                          <div class="sidebar-link-inner">
                              <span>
                                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                      viewBox="0 0 16 16">
                                      <g fill="currentColor">
                                          <path fill-rule="evenodd"
                                              d="M6 8V1h1v6.117L8.743 6.07a.5.5 0 0 1 .514 0L11 7.117V1h1v7a.5.5 0 0 1-.757.429L9 7.083L6.757 8.43A.5.5 0 0 1 6 8" />
                                          <path
                                              d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2" />
                                          <path
                                              d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1z" />
                                      </g>
                                  </svg>
                              </span>
                              <span class="hide-menu">Jurnal</span>
                          </div>
                      </a>
                  </li>

                  <li class="nav-small-cap">
                      <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                      <span class="hide-menu">Perizinan Siswa</span>
                  </li>

                  <li class="sidebar-item ">
                      <a class="sidebar-link" href="{{ route('employee.permission') }}" aria-expanded="false">
                          <div class="sidebar-link-inner">
                              <span>
                                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                      viewBox="0 0 48 48">
                                      <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="4">
                                          <path stroke-linejoin="round"
                                              d="M20 10H6a2 2 0 0 0-2 2v26a2 2 0 0 0 2 2h36a2 2 0 0 0 2-2v-2.5" />
                                          <path d="M10 23h8m-8 8h24" />
                                          <circle cx="34" cy="16" r="6" stroke-linejoin="round" />
                                          <path stroke-linejoin="round"
                                              d="M44 28.419C42.047 24.602 38 22 34 22s-5.993 1.133-8.05 3" />
                                      </g>
                                  </svg>
                              </span>
                              <span class="hide-menu">Perizinan</span>
                          </div>

                      </a>
                  </li>
              </ul>
          @endrole

          @can('view_violation')
              <ul id="sidebarnav">
                  <li class="nav-small-cap">
                      <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                      <span class="hide-menu">Pelanggaran</span>
                  </li>

                  <li class="sidebar-item ">
                      <a class="sidebar-link" href="{{ route('employee.violation.overview') }}" aria-expanded="false">

                          <div class="sidebar-link-inner">
                              <span>
                                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                      viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                      stroke-linecap="round" stroke-linejoin="round"
                                      class="icon icon-tabler icons-tabler-outline icon-tabler-activity-heartbeat">
                                      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                      <path d="M3 12h4.5l1.5 -6l4 12l2 -9l1.5 3h4.5" />
                                  </svg>
                              </span>
                              <span class="hide-menu">Overview</span>
                          </div>

                      </a>
                  </li>

                  <li class="sidebar-item ">
                      <a class="sidebar-link" href="/employee/rfid-student-violation" aria-expanded="false">
                          <div class="sidebar-link-inner">
                              <span>
                                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                      viewBox="0 0 20 20">
                                      <path fill="currentColor"
                                          d="M4.5 5.75a2.25 2.25 0 1 1 4.5 0a2.25 2.25 0 0 1-4.5 0M6.75 2.5a3.25 3.25 0 1 0 0 6.5a3.25 3.25 0 0 0 0-6.5M1.5 12a2 2 0 0 1 2-2H10a2 2 0 0 1 .993.263q-.416.346-.758.765A1 1 0 0 0 10 11H3.5a1 1 0 0 0-1 1v.078l.007.083a2.95 2.95 0 0 0 .498 1.336C3.492 14.201 4.513 15 6.75 15c.954 0 1.687-.145 2.252-.367q.013.525.12 1.02C8.476 15.87 7.695 16 6.75 16c-2.513 0-3.867-.92-4.568-1.934a3.95 3.95 0 0 1-.67-1.807a3 3 0 0 1-.012-.175zM13 6.5a1.5 1.5 0 1 1 3 0a1.5 1.5 0 0 1-3 0M14.5 4a2.5 2.5 0 1 0 0 5a2.5 2.5 0 0 0 0-5M19 14.5a4.5 4.5 0 1 1-9 0a4.5 4.5 0 0 1 9 0M14.5 12a.5.5 0 0 0-.5.5v2a.5.5 0 0 0 1 0v-2a.5.5 0 0 0-.5-.5m0 5.125a.625.625 0 1 0 0-1.25a.625.625 0 0 0 0 1.25" />
                                  </svg>
                              </span>
                              <span class="hide-menu">Tambah Pelanggaran</span>
                          </div>

                      </a>
                  </li>

                  <li class="sidebar-item ">
                      <a class="sidebar-link {{ request()->routeIs('employee.violation.student-point.index') || request()->routeIs('employee.violation.student-point.detail') || request()->routeIs('employee.violation.class-point.detail') ? 'active' : '' }}"
                          href="{{ route('employee.violation.student-point.index') }}" aria-expanded="false">
                          <div class="sidebar-link-inner">
                              <span>
                                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                      viewBox="0 0 24 24">
                                      <path fill="currentColor"
                                          d="M20 11q-.425 0-.712-.288T19 10t.288-.712T20 9t.713.288T21 10t-.288.713T20 11m-1-3V3h2v5zM9 12q-1.65 0-2.825-1.175T5 8t1.175-2.825T9 4t2.825 1.175T13 8t-1.175 2.825T9 12m-8 8v-2.8q0-.85.438-1.562T2.6 14.55q1.55-.775 3.15-1.162T9 13t3.25.388t3.15 1.162q.725.375 1.163 1.088T17 17.2V20zm2-2h12v-.8q0-.275-.137-.5t-.363-.35q-1.35-.675-2.725-1.012T9 15t-2.775.338T3.5 16.35q-.225.125-.363.35T3 17.2zm6-8q.825 0 1.413-.587T11 8t-.587-1.412T9 6t-1.412.588T7 8t.588 1.413T9 10m0 8" />
                                  </svg>
                              </span>
                              <span class="hide-menu">Daftar Point Siswa</span>
                          </div>

                      </a>
                  </li>

                  <li class="sidebar-item ">
                      <a class="sidebar-link" href="{{ route('employee.violation.students') }}" aria-expanded="false">
                          <div class="sidebar-link-inner">
                              <span>
                                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                      viewBox="0 0 24 24">
                                      <g fill="none">
                                          <path
                                              d="M24 0v24H0V0zM12.593 23.258l-.011.002l-.071.035l-.02.004l-.014-.004l-.071-.035q-.016-.005-.024.005l-.004.01l-.017.428l.005.02l.01.013l.104.074l.015.004l.012-.004l.104-.074l.012-.016l.004-.017l-.017-.427q-.004-.016-.017-.018m.265-.113l-.013.002l-.185.093l-.01.01l-.003.011l.018.43l.005.012l.008.007l.201.093q.019.005.029-.008l.004-.014l-.034-.614q-.005-.019-.02-.022m-.715.002a.02.02 0 0 0-.027.006l-.006.014l-.034.614q.001.018.017.024l.015-.002l.201-.093l.01-.008l.004-.011l.017-.43l-.003-.012l-.01-.01z" />
                                          <path fill="currentColor"
                                              d="m12.702 2.195l7 2.625A2 2 0 0 1 21 6.693v5.363a9 9 0 0 1-4.975 8.05l-3.354 1.677a1.5 1.5 0 0 1-1.342 0l-3.354-1.677A9 9 0 0 1 3 12.056V6.693A2 2 0 0 1 4.298 4.82l7-2.625a2 2 0 0 1 1.404 0M12 4.068L5 6.693v5.363a7 7 0 0 0 3.87 6.26L12 19.883l3.13-1.565A7 7 0 0 0 19 12.056V6.693zM12 14a1 1 0 1 1 0 2a1 1 0 0 1 0-2m0-7a1 1 0 0 1 1 1v4a1 1 0 1 1-2 0V8a1 1 0 0 1 1-1" />
                                      </g>
                                  </svg>
                              </span>
                              <span class="hide-menu">Daftar Pelanggaran</span>
                          </div>

                      </a>
                  </li>

                  <li class="sidebar-item ">
                      <a class="sidebar-link" href="{{ route('employee.violation.student-repair.index') }}"
                          aria-expanded="false">
                          <div class="sidebar-link-inner">
                              <span>
                                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                      viewBox="0 0 48 48">
                                      <g fill="none">
                                          <rect width="38" height="26" x="5" y="16" stroke="currentColor"
                                              stroke-linejoin="round" stroke-width="4" rx="3" />
                                          <path fill="currentColor"
                                              d="M19 8h10V4H19zm11 1v7h4V9zm-12 7V9h-4v7zm11-8a1 1 0 0 1 1 1h4a5 5 0 0 0-5-5zM19 4a5 5 0 0 0-5 5h4a1 1 0 0 1 1-1z" />
                                          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                              stroke-width="4" d="M18 29h12m-6-6v12" />
                                      </g>
                                  </svg>
                              </span>
                              <span class="hide-menu">Daftar Perbaikan Siswa</span>
                          </div>

                      </a>
                  </li>
              </ul>
          @endcan

          <div class="mt-auto px-3 pb-3 sidebar-profile">
              <div class="d-flex align-items-center gap-3 p-3 rounded-3 profile-card">
                  <img src="{{ Auth::user()->image ? asset('storage/' . Auth::user()->image) : asset('assets/images/default-user.jpeg') }}"
                      class="rounded-circle" width="44" height="44" style="object-fit: cover;">

                  <div class="flex-grow-1">
                      <div class="fw-semibold text-dark">
                          {{ auth()->user()->name }}
                      </div>
                      <small class="text-muted">
                          {{ auth()->user()->getRoleNames()->first() }}
                      </small>
                  </div>
              </div>

              <form action="{{ route('logout') }}" method="POST" class="mt-2">
                  @csrf
                  <button type="submit" class="btn btn-outline-danger w-100 rounded-pill">
                      Logout
                  </button>
              </form>
          </div>
          <style>
              /* ===============================
   RESET & STRUCTURE
================================ */
              .sidebar-nav {
                  height: 100%;
              }

              .sidebar-item {
                  padding: 0;
                  /* PENTING: biar full width */
              }

              /* reset bawaan template */
              .sidebar-nav .sidebar-item .sidebar-link {
                  padding: 0 !important;
                  background: transparent !important;
                  display: block;
              }

              /* ===============================
   MENU ITEM
================================ */
              .sidebar-link-inner {
                  display: flex;
                  align-items: center;
                  gap: 12px;
                  margin-left: 10px;
                  margin-right: 10px;
                  width: 100%;
                  padding: 12px 20px;
                  border-radius: 0;
                  transition: all .2s ease;
                  border-radius: 5px;
              }

              /* hover */
              .sidebar-link:hover .sidebar-link-inner {
                  background-color: rgba(93, 135, 255, 0.12);
              }

              /* active FULL */
              .sidebar-link.active .sidebar-link-inner {
                  background-color: #5d87ff;
                  color: #fff;
              }

              /* icon ikut warna */
              .sidebar-link svg {
                  transition: color .2s ease;
              }

              .sidebar-link.active svg,
              .sidebar-link:hover svg {
                  color: inherit;
              }

              /* ===============================
   PROFILE DI BAWAH
================================ */
              .sidebar-profile {
                  margin-top: auto;
                  padding: 16px;
                  border-top: 1px solid #f1f3f5;
                  background: #fff;
              }

              .profile-card {
                  cursor: pointer;
                  transition: background-color .2s ease;
              }

              .profile-card:hover {
                  background-color: rgba(93, 135, 255, 0.12);
              }
          </style>
      </nav>
