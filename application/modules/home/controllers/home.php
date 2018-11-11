<?php
if ( !defined( 'BASEPATH' ) )exit( 'No direct script access allowed' );


class home extends MX_Controller {

	function __construct() {
		parent::__construct();
		$this->load->helper( 'url' );
		$this->load->library( 'session' );
		$this->load->model( 'model' );
	}

	public
	function index() {
		$this->load->view( 'header' );
		if ( $this->session->userdata( 'logged_in' ) ) {
			$role = $this->session->userdata( 'logged_in' )[ 'role' ];
			if ( $role == "0" ) {
				$barang = $this->model->getDataBarang(null);
				$data[ 'barang' ] = $barang;
			} else if ( $role == "1" ){
				$pesanan = $this->model->getDataPesanan(null);
				$data[ 'pesanan' ] = $pesanan;
			}
			$this->load->view( 'beranda', $data );
		} else
			$this->load->view( 'Login' );
	}

	public
	function openPageEmail() {
		$this->load->view( 'header' );
		if ( $this->session->userdata( 'logged_in' ) ) {
			$member = $this->model->getBanyakMember();
			print_r( $this->db->last_query() );
			$pesan = $this->model->getPesan( "" );
			$data[ 'member' ] = $member;
			$data[ 'pesan' ] = $pesan;
			$this->load->view( 'pageEmail', $data );
		} else
			$this->load->view( 'Login' );
	}

	public
	function openPageMember() {
		$this->load->view( 'header' );
		if ( $this->session->userdata( 'logged_in' ) ) {
			$member = $this->model->getDataBarang();
			$data[ 'member' ] = $member;
			$this->load->view( 'pageMember', $data );
		} else
			$this->load->view( 'Login' );
	}

	public
	function openPageBeranda() {
		$this->load->view( 'header' );
		if ( $this->session->userdata( 'logged_in' ) ) {
			$role = $this->session->userdata( 'logged_in' )[ 'role' ];
			if ( $role == "0" ) {
				$barang = $this->model->getDataBarang(null);
				$data[ 'barang' ] = $barang;
			} else {
				$barang = $this->model->getDataBarang(null);
				$data[ 'barang' ] = $barang;
			}
			$this->load->view( 'beranda', $data );
		} else
			$this->load->view( 'Login' );
	}

	public
	function Login() {
		$this->load->view( 'header' );
		$this->load->view( 'Login' );
	}

	public
	function openPageBarangDetail() {
		$detailBarang = $this->model->getDataBarang( $this->uri->segment( 3 ) );
		$data[ 'barang' ] = $detailBarang;
		$this->load->view( 'header' );
		$this->load->view( 'bukaPesan', $data );
	}
	
	public
	function openPesananDetail() {
		$detailPesanan = $this->model->getDataPesananDetail( $this->uri->segment( 3 ) );
		$detailBarang = $this->model->getDataPesananBarang( $this->uri->segment( 3 ) );
		$barang = $this->model->getDataBarang( null );
		$data[ 'pesananDetail' ] = $detailPesanan;
		$data[ 'pesananBarang' ] = $detailBarang;
		$data[ 'barang' ] = $barang;
		$this->load->view( 'header' );
		$this->load->view( 'bukaPesananDetail', $data );
	}

	public
	function process() {
		$result = $this->model->validate();
		if ( !$result ) {
			$this->Login();
		} else {
			$this->load->view( 'header' );
			$role = $this->session->userdata( 'logged_in' )[ 'role' ];
			if ( $role == "0" ) {
				$barang = $this->model->getDataBarang(null);
				$data[ 'barang' ] = $barang;
			} else if ( $role == "1" ){
				$pesanan = $this->model->getDataPesanan(null);
				$data[ 'pesanan' ] = $pesanan;
			}
			$this->load->view( 'beranda', $data );
		}
	}

	public
	function processKirimPesan() {
		$result = $this->model->kirimPesan();
		if ( !$result ) {
			$this->status( "Kirim Pesan", "Kirim Pesan Gagal (ID pesanan telah digunakan)" );
		} else {
			$this->status( "Kirim Pesan", "Kirim Pesan Berhasil" );
		}
	}
	
	public
	function confirmation() {
		
		$this->load->view( 'headerExBar' );
		$this->load->view( 'confirmation' );
	}
	
	public
	function openPesananDetailConfirmation() {
		$detailPesanan = $this->model->getDataPesananDetailUser();
		$detailBarang = $this->model->getDataPesananBarangUser();
		$data[ 'pesananDetail' ] = $detailPesanan;
		$data[ 'pesananBarang' ] = $detailBarang;
		$this->load->view( 'header' );
		$this->load->view( 'bukaPesananDetailUser', $data );
	}
	
	public
	function procesDataBarangPesanan() {
		$result = $this->model->tambahDataPesananBarang();
		if ( !$result ) {
			$this->status( "Data Barang", "Stok barang tidak mencukupi" );
		} else {
			$this->status( "Data Barang", "Tambah barang sukses" );
		}
	}
	
	

	public
	function do_logout() {
		$this->session->sess_destroy();
		$this->status( "Logout", "Logout Berhasil" );
	}

	public
	function PerbaharuiBarang() {
		
		$result = $this->model->perbaharuiBarang();
		if ( !$result ) {
			$this->status( "Perbaharui Barang", "Perbaharui Barang Anda Gagal" );
		} else {
			$this->status( "Perbaharui Barang", "Perbaharui Barang Anda Berhasil" );
		}
	}
	
	public
	function prosesHapusBarang() {
		$result = $this->model->HapusBarang( $this->uri->segment( 3 ));
		if ( !$result ) {
			$this->status( "Hapus Barang", "Hapus Barang Anda Gagal" );
		} else {
			$this->status( "Hapus Barang", "Hapus Barang Anda Berhasil" );
		}
	}

	public
	function status( $status, $status2 ) {
		$data[ "status1" ] = $status;
		$data[ "status2" ] = $status2;
		$this->load->view( 'header' );
		$this->load->view( 'status', $data );
	}

	public
	function SimpanBarang() {
		$result = $this->model->simpanBarang();
		if ( !$result ) {
			$this->status( "Barang Baru", "Penambahan Barang Gagal" );
		} else {
			$this->status( "Barang Baru", "Penambahan Barang Berhasil" );
		}
	}

	public
	function kirimUlang() {
		$result = $this->model->kirimUlang( $this->uri->segment( 3 ) );
		if ( !$result ) {
			$this->status( "Kirim Ulang", "Kirim Ulang Gagal" );
		} else {
			$this->status( "Kirim Ulang", "Kirim Ulang Berhasil" );
		}
	}
}