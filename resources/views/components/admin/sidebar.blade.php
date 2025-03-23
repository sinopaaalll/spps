  <nav class="pc-sidebar">
      <div class="navbar-wrapper">
          <div class="m-header d-flex justify-content-center align-items-center">
              <a href="{{ route('dashboard') }}" class="b-brand text-primary">
                  <img src="{{ asset('assets/images/logo.png') }}" style="width: 100px;" class="mt-5 mb-2" />
              </a>
          </div>
          <div class="navbar-content">

              <ul class="pc-navbar">
                  <li class="pc-item pc-caption">
                      <label>Home</label>
                      <i class="ti ti-dashboard"></i>
                  </li>
                  <li class="pc-item {{ Request::is('dashboard') ? 'active' : '' }}">
                      <a href="{{ route('dashboard') }}" class="pc-link">
                          <span class="pc-micon">
                              <svg class="pc-icon">
                                  <use xlink:href="#custom-status-up"></use>
                              </svg>
                          </span>
                          <span class="pc-mtext">Dashboard</span>
                      </a>
                  </li>

                  <li class="pc-item pc-caption">
                      <label>Master Data</label>
                      <i class="ti ti-dashboard"></i>
                  </li>

                  <li class="pc-item {{ Request::is('tahun_ajaran*') ? 'active' : '' }}">
                      <a href="{{ route('tahun_ajaran.index') }}" class="pc-link">
                          <span class="pc-micon">
                              <svg class="pc-icon">
                                  <use xlink:href="#custom-notification-status"></use>
                              </svg>
                          </span>
                          <span class="pc-mtext">Tahun Ajaran</span>
                      </a>
                  </li>
                  <li class="pc-item {{ Request::is('kelas*') ? 'active' : '' }}">
                      <a href="{{ route('kelas.index') }}" class="pc-link">
                          <span class="pc-micon">
                              <svg class="pc-icon">
                                  <use xlink:href="#custom-document-filter"></use>
                              </svg>
                          </span>
                          <span class="pc-mtext">Kelas</span>
                      </a>
                  </li>
                  <li class="pc-item {{ Request::is('siswa*') ? 'active' : '' }}">
                      <a href="{{ route('siswa.index') }}" class="pc-link">
                          <span class="pc-micon">
                              <svg class="pc-icon">
                                  <use xlink:href="#custom-user-square"></use>
                              </svg>
                          </span>
                          <span class="pc-mtext">Siswa</span>
                      </a>
                  </li>

                  <li class="pc-item pc-caption">
                      <label>Transaksi</label>
                      <i class="ti ti-dashboard"></i>
                  </li>

                  <li class="pc-item {{ Request::is('payout*') ? 'active' : '' }}">
                      <a href="{{ route('payout.index') }}" class="pc-link">
                          <span class="pc-micon">
                              <svg class="pc-icon">
                                  <use xlink:href="#custom-dollar-square"></use>
                              </svg>
                          </span>
                          <span class="pc-mtext">Pembayaran Siswa</span>
                      </a>
                  </li>

                  <li class="pc-item pc-caption">
                      <label>Pembayaran</label>
                      <i class="ti ti-dashboard"></i>
                  </li>
                  <li class="pc-item {{ Request::is('pos*') ? 'active' : '' }}">
                      <a href="{{ route('pos.index') }}" class="pc-link">
                          <span class="pc-micon">
                              <svg class="pc-icon">
                                  <use xlink:href="#custom-notification-status"></use>
                              </svg>
                          </span>
                          <span class="pc-mtext">Pos Pembayaran</span>
                      </a>
                  </li>
                  <li class="pc-item {{ Request::is('jenis_pembayaran*') ? 'active' : '' }}">
                      <a href="{{ route('jenis_pembayaran.index') }}" class="pc-link">
                          <span class="pc-micon">
                              <svg class="pc-icon">
                                  <use xlink:href="#custom-notification-status"></use>
                              </svg>
                          </span>
                          <span class="pc-mtext">Jenis Pembayaran</span>
                      </a>
                  </li>

                  <li class="pc-item pc-caption">
                      <label>Laporan</label>
                      <i class="ti ti-dashboard"></i>
                  </li>
                  <li class="pc-item {{ Request::is('laporan*') ? 'active' : '' }}">
                      <a href="{{ route('laporan.index') }}" class="pc-link">
                          <span class="pc-micon">
                              <svg class="pc-icon">
                                  <use xlink:href="#custom-calendar-1"></use>
                              </svg>
                          </span>
                          <span class="pc-mtext">Laporan Keuangan</span>
                      </a>
                  </li>

                  <li class="pc-item pc-caption">
                      <label>Setting</label>
                      <i class="ti ti-dashboard"></i>
                  </li>
                  <li class="pc-item {{ Request::is('bulan*') ? 'active' : '' }}">
                      <a href="{{ route('bulan.index') }}" class="pc-link">
                          <span class="pc-micon">
                              <svg class="pc-icon">
                                  <use xlink:href="#custom-setting-2"></use>
                              </svg>
                          </span>
                          <span class="pc-mtext">Bulan</span>
                      </a>
                  </li>



              </ul>
          </div>
      </div>
  </nav>
