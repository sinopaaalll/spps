  <nav class="pc-sidebar">
      <div class="navbar-wrapper">
          <div class="m-header">
              <a href="#" class="b-brand text-primary">
                  <!-- ========   Change your logo from here   ============ -->
                  <img src="{{ asset('assets/images/logo-dark.svg') }}" />
                  <span class="badge bg-light-success rounded-pill ms-2 theme-version"></span>
              </a>
          </div>
          <div class="navbar-content">
              <div class="card pc-user-card">
                  <div class="card-body">
                      <div class="d-flex align-items-center">
                          <div class="flex-shrink-0">
                              <img src="{{ asset('assets/images/user/avatar-1.jpg') }}" alt="user-image"
                                  class="user-avtar wid-45 rounded-circle" />
                          </div>
                          <div class="flex-grow-1 ms-3 me-2">
                              <h6 class="mb-0">Jonh Smith</h6>
                              <small>Administrator</small>
                          </div>
                          <a class="btn btn-icon btn-link-secondary avtar-s" data-bs-toggle="collapse"
                              href="#pc_sidebar_userlink">
                              <svg class="pc-icon">
                                  <use xlink:href="#custom-sort-outline"></use>
                              </svg>
                          </a>
                      </div>
                      <div class="collapse pc-user-links" id="pc_sidebar_userlink">
                          <div class="pt-3">
                              <a href="#!">
                                  <i class="ti ti-user"></i>
                                  <span>My Account</span>
                              </a>
                              <a href="#!">
                                  <i class="ti ti-settings"></i>
                                  <span>Settings</span>
                              </a>
                              <a href="#!">
                                  <i class="ti ti-lock"></i>
                                  <span>Lock Screen</span>
                              </a>
                              <a href="#!">
                                  <i class="ti ti-power"></i>
                                  <span>Logout</span>
                              </a>
                          </div>
                      </div>
                  </div>
              </div>

              <ul class="pc-navbar">
                  <li class="pc-item pc-caption">
                      <label>Navigation</label>
                      <i class="ti ti-dashboard"></i>
                  </li>
                  <li class="pc-item">
                      <a href="../widget/w_statistics.html" class="pc-link">
                          <span class="pc-micon">
                              <svg class="pc-icon">
                                  <use xlink:href="#custom-status-up"></use>
                              </svg>
                          </span>
                          <span class="pc-mtext">Dashboard</span>
                      </a>
                  </li>
                  <li class="pc-item">
                      <a href="{{ route('kelas.index') }}" class="pc-link active">
                          <span class="pc-micon">
                              <svg class="pc-icon">
                                  <use xlink:href="#custom-status-up"></use>
                              </svg>
                          </span>
                          <span class="pc-mtext">Kelas</span>
                      </a>
                  </li>
                  <li class="pc-item pc-hasmenu">
                      <a href="#!" class="pc-link">
                          <span class="pc-micon">
                              <svg class="pc-icon">
                                  <use xlink:href="#custom-status-up"></use>
                              </svg>
                          </span>
                          <span class="pc-mtext">Dashboard</span>
                          <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                          <span class="pc-badge">2</span>
                      </a>
                      <ul class="pc-submenu">
                          <li class="pc-item"><a class="pc-link" href="../dashboard/index.html">Default</a></li>
                          <li class="pc-item"><a class="pc-link" href="../dashboard/analytics.html">Analytics</a></li>
                      </ul>
                  </li>
              </ul>
          </div>
      </div>
  </nav>
