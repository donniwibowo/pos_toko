<?php
    use App\Models\ProdukStokModel;

    $today = date("Y-m-d");
    $date = date('Y-m-d', strtotime('+3 month', strtotime($today)));
    $last_date = date('Y-m-t', strtotime($date));

    $total_notif = 0;
    $produk_stok_model = new ProdukStokModel();

    $db      = \Config\Database::connect();
    $builder = $db->table('tbl_produk p');
    $builder->select('p.*, s.stok');
    $builder->selectSum('s.stok');
    
    $subQuery = $db->table('tbl_produk_stok ps');
    $subQuery->selectSum('ps.stok', false);
    $subQuery->where('ps.produk_id = p.produk_id');
    $subQuery->where('ps.is_deleted', '0');
    $subQuery->groupBy('ps.produk_id');

    $builder->where('p.is_deleted', 0);
    $builder->where('s.is_deleted', 0);
    $builder->where('p.stok_min >=', $subQuery);
    $builder->join('tbl_produk_stok s', 's.produk_id = p.produk_id');
    $builder->groupBy('p.produk_id');
    $query_stok = $builder->get();

    if($query_stok->getResult()) {
         $total_notif += count($query_stok->getResult());
    }
    


    $builder = $db->table('tbl_produk');
    $builder->select('tbl_produk.*, tbl_kategori.kategori_id, tbl_kategori.nama_kategori, tbl_supplier.supplier_id, tbl_supplier.nama_supplier, tbl_produk_stok.stok_id, tbl_produk_stok.stok, tbl_produk_stok.tgl_kadaluarsa');
    $builder->where('tbl_produk.is_deleted', 0);
    $builder->where('tbl_produk_stok.is_deleted', 0);
    $builder->where('tbl_produk_stok.tgl_kadaluarsa <=', $last_date);
    $builder->join('tbl_produk_stok', 'tbl_produk.produk_id = tbl_produk_stok.produk_id');
    $builder->join('tbl_supplier', 'tbl_produk.supplier_id = tbl_supplier.supplier_id');
    $builder->join('tbl_kategori', 'tbl_produk.kategori_id = tbl_kategori.kategori_id');
    $query_ed   = $builder->get();
   
    if($query_ed->getResult()) {
         $total_notif += count($query_ed->getResult());
    }
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SMART POS</title>
  <link rel="shortcut icon" type="image/png" href="<?= base_url() ?>assets/images/logos/smart-pos-favicon.png" />
  <link rel="stylesheet" href="<?= base_url() ?>assets/css/styles.min.css" />
  <link rel="stylesheet" href="//cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />
  <link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap-datepicker.min.css" />
  <!-- <link href='https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/ui-lightness/jquery-ui.css' rel='stylesheet'> -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="<?= base_url() ?>assets/css/custom.css" />
</head>

<body>
  <input type="hidden" id="baseUrl" value="<?php echo base_url(); ?>">
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <!-- Sidebar Start -->
    <aside class="left-sidebar">
      <!-- Sidebar scroll-->
      <div>
        <div class="brand-logo d-flex align-items-center justify-content-between pt-4">
          <a href="javascript:;" class="text-nowrap logo-img">
            <img src="<?= base_url() ?>assets/images/logos/smart-pos-logo.png" width="180" alt="" />
          </a>
          <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
            <i class="ti ti-x fs-8"></i>
          </div>
        </div>
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
          <ul id="sidebarnav">
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">MENU</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="<?= base_url('penjualan/harian') ?>" aria-expanded="false">
                <span>
                  <i class="ti ti-home"></i>
                </span>
                <span class="hide-menu">Dashboard</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="<?= base_url('user/list') ?>" aria-expanded="false">
                <span>
                  <i class="ti ti-user-plus"></i>
                </span>
                <span class="hide-menu">User</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="<?= base_url('supplier/list') ?>" aria-expanded="false">
                <span>
                  <i class="ti ti-building-factory-2"></i>
                </span>
                <span class="hide-menu">Supplier</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="<?= base_url('kategori/list') ?>" aria-expanded="false">
                <span>
                  <i class="ti ti-article"></i>
                </span>
                <span class="hide-menu">Kategori</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link has-arrow" href="javascript:;" aria-expanded="false">
                <span>
                  <i class="ti ti-layout-dashboard"></i>
                </span>
                <span class="hide-menu">Produk</span>
              </a>

              <ul aria-expanded="false" class="collapse first-level">
                  <li class="sidebar-item">
                    <a href="<?= base_url('produk/list') ?>" class="sidebar-link">
                      <div class="round-16 d-flex align-items-center justify-content-center">
                        <i class="ti ti-circle"></i>
                      </div>
                      <span class="hide-menu">Semua Produk</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="<?= base_url('produk/listbystock') ?>" class="sidebar-link">
                      <div class="round-16 d-flex align-items-center justify-content-center">
                        <i class="ti ti-circle"></i>
                      </div>
                      <span class="hide-menu">Stok Produk</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="<?= base_url('produk/listbyed') ?>" class="sidebar-link">
                      <div class="round-16 d-flex align-items-center justify-content-center">
                        <i class="ti ti-circle"></i>
                      </div>
                      <span class="hide-menu">Kadaluarsa Produk</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="<?= base_url('produk/listdiskon') ?>" class="sidebar-link">
                      <div class="round-16 d-flex align-items-center justify-content-center">
                        <i class="ti ti-circle"></i>
                      </div>
                      <span class="hide-menu">Produk Diskon</span>
                    </a>
                  </li>
              </ul>
            </li>
            

            <li class="sidebar-item">
              <a class="sidebar-link has-arrow" href="javascript:;" aria-expanded="false">
                <span>
                  <i class="ti ti-cash"></i>
                </span>
                <span class="hide-menu">Penjualan</span>
              </a>

              <ul aria-expanded="false" class="collapse first-level">
                  <li class="sidebar-item">
                    <a href="<?= base_url('penjualan/list') ?>" class="sidebar-link">
                      <div class="round-16 d-flex align-items-center justify-content-center">
                        <i class="ti ti-circle"></i>
                      </div>
                      <span class="hide-menu">Daftar Penjualan</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="<?= base_url('penjualan/analisa') ?>" class="sidebar-link">
                      <div class="round-16 d-flex align-items-center justify-content-center">
                        <i class="ti ti-circle"></i>
                      </div>
                      <span class="hide-menu">Basket Analysis</span>
                    </a>
                  </li>

                  <li class="sidebar-item">
                    <a href="<?= base_url('penjualan/report') ?>" class="sidebar-link">
                      <div class="round-16 d-flex align-items-center justify-content-center">
                        <i class="ti ti-circle"></i>
                      </div>
                      <span class="hide-menu">Laporan</span>
                    </a>
                  </li>
                  
              </ul>
            </li>

            <li class="sidebar-item">
              <a class="sidebar-link" href="<?= base_url('pembelian/list') ?>" aria-expanded="false">
                <span>
                  <i class="ti ti-article"></i>
                </span>
                <span class="hide-menu">Pembelian</span>
              </a>
            </li>

            <li class="sidebar-item">
              <a class="sidebar-link" href="<?= base_url('setting/update') ?>" aria-expanded="false">
                <span>
                  <i class="ti ti-settings-automation"></i>
                </span>
                <span class="hide-menu">Pengaturan</span>
              </a>
            </li>
            
          </ul>
        </nav>
        <!-- End Sidebar navigation -->
      </div>
      <!-- End Sidebar scroll-->
    </aside>
    <!--  Sidebar End -->
    <!--  Main wrapper -->
    <div class="body-wrapper">
      <!--  Header Start -->
      <header class="app-header">
        <nav class="navbar navbar-expand-lg navbar-light">
          <ul class="navbar-nav">
            <li class="nav-item d-block d-xl-none">
              <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="javascript:void(0)">
                <i class="ti ti-menu-2"></i>
              </a>
            </li>
          </ul>
          <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
            <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
              <li class="nav-item dropdown">
                <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="ti ti-bell-ringing"></i>
                  <?php if($total_notif > 0) : ?>
                    <div class="notification bg-primary rounded-circle"></div>
                  <?php endif; ?>
                </a>
                <div class="dropdown-menu content-dd dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                  <div class="d-flex align-items-center justify-content-between py-3 px-7" style="padding-left: 16px !important;">
                    <h5 class="mb-0 fs-5 fw-semibold">Notifikasi</h5>
                    <span class="badge bg-primary rounded-4 px-3 py-1 lh-sm"><?= $total_notif ?></span>
                  </div>
                  <div class="message-body" data-simplebar>
                    

                    <ul class="nav nav-underline" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link d-flex active" data-bs-toggle="tab" href="#home2" role="tab" >
                          <span class="d-none d-md-block ms-2">Stok</span>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link d-flex" data-bs-toggle="tab" href="#profile2" role="tab">
                          <span class="d-none d-md-block ms-2">Kadaluarsa</span>
                        </a>
                      </li>
                     
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content">
                      <div class="tab-pane active" id="home2" role="tabpanel">
                        <div class="p-3">
                          <?php
                            if($query_stok) {
                              foreach($query_stok->getResult() as $produk) {
                                echo "<a href='javascript:void(0)'' class='py-6 d-flex align-items-center dropdown-item'>".$produk->nama_produk." (".$produk_stok_model->convertStok($produk->stok_min, $produk->netto, $produk->satuan_terkecil).")</a>";
                              }
                            }

                          ?>
                         
                        </div>

                        <div class="py-6 px-7 mb-1">
                          <a href="<?= base_url().'produk/listbystock/' ?>" role="button" class="btn btn-outline-primary w-100">Lihat Semua</a>
                        </div>
                      </div>

                      <div class="tab-pane" id="profile2" role="tabpanel">
                        <div class="p-3">
                          <?php
                            if($query_ed) {
                              foreach($query_ed->getResult() as $produk) {
                                echo "<a href='javascript:void(0)'' class='py-6 d-flex align-items-center dropdown-item'>".$produk->nama_produk." (".$produk_stok_model->convertStok($produk->stok_min, $produk->netto, $produk->satuan_terkecil).")</a>";
                              }
                            }

                          ?>
                        </div>

                        <div class="py-6 px-7 mb-1">
                          <a href="<?= base_url().'produk/listbyed/' ?>" role="button" class="btn btn-outline-primary w-100">Lihat Semua</a>
                        </div>
                      </div> <!-- end of tab pane profile -->

                    </div> <!-- end of tab content -->
                  </div> <!-- end of message-body -->
                 
              </li>


             <li class="nav-item dropdown">
                <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown"
                  aria-expanded="false">
                  <img src="<?= base_url() ?>assets/images/profile/user-1.jpg" alt="" width="35" height="35" class="rounded-circle">
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                  <div class="py-3 px-7 pb-0">
                        <h5 class="mb-0 fs-5 fw-semibold">User Profile</h5>
                      </div>
                  <div class="d-flex align-items-center py-9 mx-7 border-bottom">
                    <img src="<?= base_url() ?>assets/images/profile/user-1.jpg" class="rounded-circle" width="80" height="80"
                      alt="" />
                    <div class="ms-3">
                      <h5 class="mb-1 fs-3"><?= ucwords(strtolower(session()->nama)) ?></h5>
                      <span class="mb-1 d-block text-dark"><?= ucwords(strtolower(session()->jabatan)) ?></span>
                      <p class="mb-0 d-flex text-dark align-items-center gap-2">
                        <i class="ti ti-phone fs-4"></i><?= ucwords(strtolower(session()->no_telp)) ?>
                      </p>
                    </div>
                  </div>
                  <div class="message-body">
                    <a href="<?= base_url('user/logout') ?>" class="btn btn-outline-primary mx-3 mt-2 d-block">Logout</a>
                  </div>
                </div>
              </li>

            </ul>
          </div>
        </nav>
      </header>
      <!--  Header End -->