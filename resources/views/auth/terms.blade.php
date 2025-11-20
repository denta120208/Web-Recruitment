@extends('layouts.app')

@section('title', 'Syarat & Ketentuan')

@section('styles')
<style>
    /* Make the checkbox accent use the site's secondary color so it doesn't blend in */
    .form-check-input {
        accent-color: var(--secondary-color);
    }

    /* Fallback for browsers not supporting accent-color */
    .form-check-input:checked {
        background-color: var(--secondary-color);
        border-color: var(--secondary-color);
    }

    .form-check-input:focus {
        box-shadow: 0 0 0 0.15rem rgba(52, 152, 219, 0.25);
    }
</style>
@endsection

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Syarat & Ketentuan Pengelolaan Data</div>
                <div class="card-body">
                   
                    <p>1.	Pendahuluan
Selamat datang di situs Metland Career (selanjutnya disebut dengan “Situs”). Situs ini merupakan milik dan dikelola oleh PT Metropolitan Land Tbk (selanjutnya disebut “Perusahaan”). Dengan mengakses, mendaftar dan menggunakan, maka Anda dianggap telah membaca, memahami, dan secara sadar dan tanpa paksaan menyetujui Syarat dan Ketentuan Situs (selanjutnya disebut “Syarat dan Ketentuan”) ini. Perusahaan mengumpulkan dan menggunakan informasi pribadi Anda sesuai dengan Kebijakan Privasi Perusahaan.

2.	Membuat Akun
Situs ini terbuka untuk individu yang secara hukum dianggap dewasa (Ketentuan usia minimal 18 tahun dan kapasitas hukum untuk mengadakan perjanjian di Indonesia sudah jelas dan sesuai dengan hukum yang berlaku untuk menandatangani perjanjian. Anda menyatakan dan menjamin bahwa Anda adalah individu yang secara hukum berhak dan cakap untuk mengadakan dan mengikatkan diri dalam perjanjian berdasarkan hukum Negara Republik Indonesia. Apabila ketentuan tersebut tidak terpenuhi, Perusahaan berhak berdasarkan hukum untuk membatalkan setiap perjanjian yang dibuat dengan Anda. Anda selanjutnya menyatakan dan menjamin bahwa Anda memiliki hak, wewenang dan kapasitas untuk menggunakan Situs.
Alamat email individu yang valid diperlukan untuk bergabung dengan Situs. Dua atau lebih individu tidak boleh menggunakan alamat email yang sama. Dengan mendaftar, Anda setuju untuk menerima komunikasi elektronik yang berkaitan dengan pengoperasian, termasuk pesan informasi, informasi mengenai pengoperasian akun Anda. Anda kapan saja dapat berhenti menggunakan Situs ini, termasuk mengajukan permintaan penghapusan akun (apabila Anda telah melakukan pendaftaran sebagai pengguna Situs). Mohon diperhatikan bahwa penggunaan Situs tunduk pada Ketentuan yang Perusahaan keluarkan dan/atau perbaharui dari waktu ke waktu.
Untuk mendaftar dalam Situs, Anda harus membuat akun Anda dengan mengisi informasi yang diperlukan dalam formulir pendaftaran, termasuk nama pengguna. Anda menyatakan bahwa informasi yang diberikan dalam formulir pendaftaran adalah benar dan lengkap. Perusahaan tidak bertanggung jawab jika terdapat kesalahan atau informasi yang tidak benar/valid yang Anda berikan. Proses pendaftaran/registrasi diatur sebagai berikut:
a.	Batas minimal usia untuk mendaftarkan diri sebagai Anda adalah 18 tahun pada saat mendaftar.
b.	Anda diminta untuk mendaftarkan email, satu nomor handphone. Satu kartu identitas hanya berlaku untuk satu akun.
c.	Jika nomor handphone dan alamat email yang digunakan belum terdaftar pada Situs ini, maka Anda akan diarahkan untuk melakukan proses pendaftaran terlebih dahulu, dengan mengisi data nama, alamat email dan nomor handphone, kemudian Anda akan mendapatkan verifikasi kode OTP yang akan dikirimkan sesuai alamat email yang sudah terdaftar atas nomor handphone tersebut.
d.	Setelah proses verifikasi kode OTP berhasil, maka Anda diarahkan untuk membuat Password dan Konfirmasi Password.
e.	Anda sudah dapat melakukan proses login dengan menggunakan email atau nomor handphone yang telah diinformasikan melalui email. 

Login adalah sebuah proses dimana Anda telah terdaftar menjadi Anda dan ingin menggunakan Situs. Pada halaman Login, Anda dapat menggunakan email atau nomor handphone yang sama dengan yang digunakan pada Situs. Hal ini bertujuan untuk melanjutkan data dan progress yang sudah ada pada Situs.

3.	Akurasi Data dan Keamanan Akun 
Akun Anda bersifat sangat pribadi dan rahasia. Anda bertanggung jawab penuh untuk menyimpan dan menggunakannya. Perusahaan tidak bertanggung jawab kepada Anda atas segala kerugian yang dialami karena adanya kesalahan dan penyalahgunaan akses akun Anda yang tidak sah, curang, atau tidak pantas.
Anda bertanggung jawab sepenuhnya untuk menjaga kerahasiaan kata sandi, nomor keanggotaan, dan informasi akses akun lainnya serta untuk membatasi akses ke perangkat dan akun Anda, termasuk, tanpa batasan, akun email apa pun yang terkait dengan akun Anda, sehingga orang lain tidak dapat mengakses akun Anda, dan Perusahaan tidak akan bertanggung jawab atas kegagalan Anda untuk melakukannya.



4.	Penangguhan atau penghentian akun
Setiap pelanggaran oleh Anda terhadap Syarat dan Ketentuan ini, penyampaian informasi palsu dapat mengakibatkan penangguhan sementara keanggotaan atau penghentian akun Anda tanpa pemberitahuan. 

5.	Kewajiban Informasi dan Data Akun
Perusahaan mengumpulkan dan memproses informasi pribadi Anda termasuk namun tidak terbatas kepada nama, alamat, surat elektronik, nomor telepon Anda ketika Anda mendaftarkan diri pada Situs. Anda wajib untuk memberikan informasi yang akurat dan lengkap serta memperbaharui informasi dan setuju untuk memberikan kepada Perusahaan bukti identitas apapun yang secara wajar Perusahaan mintakan agar Perusahaan dapat tetap menyediakan layanan melalui Situs secara utuh dan maksimal kepada Anda.
Setelah mendaftarkan diri pada Situs, Anda akan mendapatkan suatu akun pribadi yang dapat diakses dengan kata sandi yang Anda pilih. Anda berjanji untuk tidak menyerahkan, mengalihkan maupun memberikan wewenang kepada orang lain untuk menggunakan identitas Anda atau menggunakan akun Anda. Anda wajib menjaga kerahasiaan kata sandi akun Anda dan setiap identifikasi yang Perusahaan berikan kepada atas akun atau identitas pribadi Anda. Dalam hal terjadi pengungkapan kata sandi atas akun Anda dengan cara apapun yang terjadi bukan atas kesalahan Perusahaan dan mengakibatkan penggunaan yang tidak sah dan/atau tanpa kewenangan atas akun Anda, transaksi maupun pesanan yang terjadi dalam Situs tetap dianggap sebagai transaksi yang sah kecuali Anda memberitahu Perusahaan sebelumnya.
Anda wajib melaporkan kepada Perusahaan bila Anda kehilangan kendali atas akun Anda. Anda bertanggung jawab atas setiap penggunaan akun Situs Anda meskipun jika Akun tersebut telah disalahgunakan oleh pihak lain

6.	Perubahan Syarat dan Ketentuan
Perusahaan berhak untuk mengubah Syarat dan Ketentuan ini dari waktu ke waktu dengan mempostingnya di Situs. Syarat dan Ketentuan yang diperbarui akan berlaku efektif sejak saat diposting, atau pada tanggal selanjutnya atau dengan metode lain yang ditentukan oleh Perusahaan. Kecuali dinyatakan lain, Syarat dan Ketentuan yang diperbarui akan berlaku pada saat Anda mengakses dan menggunakan Situs.
Situs ini mungkin berisi outbond links ke situs web lainnya yang dioperasikan oleh Perusahaan. Saat menggunakan situs-situs web tersebut, Anda perlu menyetujui syarat dan ketentuan terpisah yang mungkin berbeda dengan Syarat dan Ketentuan ini.

7.	Larangan
Dengan mengakses dan menggunakannya Situs ini, Anda dianggap telah menyetujui Syarat dan Ketentuan. 
Dalam menggunakan Situs, Anda tidak boleh terlibat dalam salah satu tindakan berikut ini:
a.	Tindakan pelanggaran atas properti atau privasi, dan lain-lain, dari Perusahaan dan/atau pihak ketiga, atau melakukan tindakan apapun yang dapat menyebabkan pelanggaran tersebut.
b.	Setiap tindakan yang menyebabkan kerugian atau kerusakan kepada Perusahaan dan/atau pihak ketiga.
c.	Setiap tindakan melanggar hukum seperti pengumpulan, pengungkapan, pemalsuan atau penghapusan informasi Perusahaan dan/atau pihak ketiga (antara lain, informasi yang terdaftar, informasi riwayat penggunaan dan informasi lainnya), atau tindakan apapun yang dapat menyebabkan hal tersebut.
d.	Setiap tindakan yang mengakses Situs (termasuk server dan jaringan yang terhubung dengan Situs) tanpa izin atau dengan cara lain secara melanggar hukum, atau tindakan lainnya yang menghambat penggunaan atau operasinya, atau tindakan yang dapat menyebabkan hal tersebut termasuk tindakan-tindakan sehubungan dengannya yang menyebabkan kerugian bagi Perusahaan dan/atau pihak ketiga.
e.	Setiap tindakan yang melanggar ketertiban umum, kesusilaan atau moral, atau tindakan yang dapat menyebabkan hal tersebut.
f.	Setiap tindakan melanggar hukum atau tindakan yang berhubungan dengan tindakan melanggar hukum, atau tindakan yang dapat menyebabkan hal tersebut.
g.	Setiap tindakan pernyataan atau deklarasi palsu atau penyerahan termasuk memberikan informasi yang tidak benar atau menyesatkan.
h.	Setiap tindakan menggunakan Situs untuk mendapatkan keuntungan yang tidak sesuai dengan tujuan dari Situs ini, atau setiap tindakan dalam mempersiapkan hal tersebut. 
i.	Setiap tindakan yang mencemarkan atau merusak nama baik Perusahaan dan/atau pihak ketiga manapun.
j.	Setiap tindakan yang menggunakan atau memberikan program yang merusak seperti virus komputer atau program perusak piranti lunak lainnya (termasuk namun tidak terbatas pada malware, trojan horse, dan lain-lain) atau setiap tindakan yang dapat menyebabkan hal tersebut.
k.	Setiap tindakan yang menggunakan Situs dengan berpura-pura menjadi pihak ketiga.
l.	Setiap tindakan yang melanggar Syarat dan Ketentuan, dan Kebijakan Privasi, atau setiap tindakan yang dapat menyebabkan hal tersebut.
m.	Setiap tindakan yang melanggar hukum dan peraturan baik di dalam maupun di luar negeri, setiap tindakan yang dapat menyebabkan hal tersebut, atau setiap tindakan yang melanggar tindakan administratif yang mengikat secara hukum.
n.	Setiap tindakan lain yang dianggap tidak pantas oleh Perusahaan dan/atau mengakibatkan adanya tanggung jawab pada Perusahaan, baik secara langsung maupun tidak langsung.
o.	Perusahaan berhak mengambil langkah hukum yang relevan terhadap Anda dalam setiap pelanggaran atas larangan-larangan di atas sesuai dengan ketentuan hukum dan/atau peraturan perundang-undangan yang berlaku.

8.	Batasan Tanggung Jawab
Perusahaan menyediakan Situs ini sebagaimana adanya dengan selalu mengusahakan kualitas dan sistem pengamanan yang sebaik-baiknya. Namun Perusahaan tidak menjamin bahwa Situs sepenuhnya bebas dari error, bug, gangguan, kerusakan atau cacat lainnya. Perusahaan dibebaskan dari tanggung jawab atas kerugian akibat force majeure atau penangguhan layanan.
Perusahaan tidak membuat jaminan apapun mengenai keakuratan, ketepatan waktu, kegunaan, atau karakteristik lainnya yang terkait dengan informasi yang diterbitkan dalam Situs. Perusahaan dapat, dengan kebijakan Perusahaan sendiri, menambah, mengubah, mengoreksi, atau menghapus informasi yang diterbitkan dalam Situs setiap saat tanpa memberikan pemberitahuan sebelumnya kepada Anda sepanjang diperkenankan oleh hukum dan/atau peraturan perundang-undangan yang berlaku. Dalam hal penambahan, perubahan, koreksi atau penghapusan informasi Situs menyebabkan kerugian bagi Anda, maka ketentuan Pasal 10 akan berlaku.
Perusahaan dapat, dengan kebijakan Perusahaan sendiri, menangguhkan atau menghentikan Situs secara keseluruhan atau sebagian, untuk sebab-sebab yang termasuk, namun tidak terbatas pada, sebagai berikut:
a.	Dalam hal adanya pemeliharaan atau perawatan secara berkala atau keadaan darurat atau peningkatan peralatan dan sistem untuk publikasi Situs.
b.	Dalam hal layanan atau penampilan Situs sulit untuk dilaksanakan karena hal-hal yang berada di luar kendali Perusahaan seperti kebakaran, mati listrik, bencana alam, keadaan perang atau darurat sipil/militer, gangguan telekomunikasi (termasuk pemutusan jaringan internet) dan lain sebagainya yang berada di luar kendali Perusahaan.
c.	Dalam hal layanan telekomunikasi tidak disediakan oleh operator telekomunikasi.
d.	Saat Perusahaan telah menetapkan penangguhan sementara atau pemberhentian Situs dibutuhkan untuk operasional atau alasan-alasan teknis, atau Perusahaan telah menetapkan atas keadaan yang tidak terduga, maka layanan atau penampilan Situs terlalu sulit untuk dilakukan.
e.	Dalam hal adanya perintah dari aparat yang berwenang dan/ atau suatu putusan pengadilan berdasarkan hukum dan/atau peraturan perundang-undangan yang berlaku.
f.	Dalam hal Perusahaan memiliki alasan untuk percaya bahwa suatu serangan siber atau gangguan keamanan atas sistem yang kredibel telah, sedang, atau akan terjadi dalam waktu dekat.

Dalam hal setiap kerugian atau kerusakan terjadi pada Anda atau pihak ketiga karena penangguhan sementara atau penghentian Situs atau penampilan Situs, ketentuan Pasal 10 akan berlaku.
Anda dapat mengakhiri penggunaan Situs dengan menghapus Akun Anda yang telah didaftarkan dan diakses dalam perangkat yang digunakan.
Bahkan jika Anda mengakhiri penggunaan Situs, informasi Anda yang diperoleh oleh Perusahaan melalui Situs tidak akan dihapus. Penanganan informasi tersebut dari Anda akan disesuaikan dan diperlakukan sesuai dengan ketentuan dalam Kebijakan Privasi serta hukum dan/atau peraturan perundang-undangan yang berlaku tentang data pribadi.
Dalam hal Anda melanggar ketentuan dalam Syarat dan Ketentuan, Perusahaan memiliki hak untuk menghilangkan atau memberhentikan sementara hak Anda berdasarkan Syarat dan Ketentuan dan hak Anda untuk menggunakan Situs tanpa pemberitahuan sebelumnya kepada Anda. Perusahaan tidak akan bertanggung jawab atas kerugian atau kerusakan yang terjadi pada Anda sebagai akibat dari Perusahaan dalam melaksanakan hak-hak yang tertera dalam butir ini. Untuk tujuan pengakhiran hak Anda di atas, Anda setuju untuk mengabaikan Pasal 1266 Kitab Undang-Undang Hukum perdata.
Dalam hal sebuah tautan dari Situs mengacu ke situs web atau aplikasi pihak ketiga, Perusahaan tidak akan bertanggung jawab atas setiap situs web atau aplikasi pihak ketiga selain Situs ini. Dalam keadaan tersebut, Perusahaan tidak akan bertanggung jawab atas isi, iklan, produk, layanan, dan lain-lain, termasuk konten maupun isi dalam situs web atau aplikasi pihak ketiga tersebut dan yang tersedia untuk penggunaan situs web atau aplikasi pihak ketiga tersebut termasuk tindakan-tindakan Anda pada situs web atau aplikasi pihak ketiga tersebut.  Perusahaan, tidak akan bertanggung jawab untuk memberikan kompensasi atas kerusakan yang diakibatkan oleh atau terjadi karena berhubungan dengan setiap isi, iklan, produk, layanan, dan lain sebagainya, yang bukan merupakan produk dari Perusahaan dan/atau Manajemen. Dengan menyediakan tautan atau menyediakan akses ke situs web atau aplikasi pihak ketiga, Perusahaan tidak memberikan setiap pernyataan, jaminan atau pengesahan, secara tegas atau tersirat, berkenaan dengan legalitas, akurasi, kualitas atau keaslian konten, informasi atau jasa yang disediakan oleh situs web atau aplikasi pihak ketiga tersebut.

9.	Merek Dagang / Hak Kekayaan Intelektual
Anda tidak boleh menyalin, membuat ulang, mendistribusikan, mengirimkan, menyiarkan, menampilkan, menjual, melisensikan atau mengeksploitasi setiap Merek dagang yang terdapat dalam Situs untuk tujuan apapun.

10.	Ganti Rugi
Perusahaan tidak akan bertanggung jawab atas setiap kerusakan atau kerugian apapun yang terjadi pada Anda atau pihak ketiga yang berhubungan dengan perubahan, gangguan, penangguhan, pemberhentian atau pengakhiran dan lain sebagainya pada Situs sejauh mana diperkenankan oleh hukum dan/atau peraturan perundang-undangan yang berlaku.
Perusahaan tidak akan bertanggung jawab atas kerusakan yang disebabkan oleh peristiwa atau kejadian tertentu yang berada di luar dugaan Perusahaan maupun yang bersifat force majeure atau dikarenakan keadaan terpaksa sepanjang diperkenankan oleh hukum dan/atau peraturan perundang-undangan yang berlaku.
Dalam hal Anda menyebabkan kerusakan dan/atau kerugian kepada pihak ketiga sehubungan dengan penggunaan Situs, Anda harus bertanggung jawab untuk menyelesaikan kerusakan dan/atau kerugian tersebut dengan biaya Anda dan tidak akan meminta Perusahaan untuk bertanggung jawab atas tindakan yang dilakukan oleh Anda sendiri. Dalam hal Anda menyebabkan kerusakan dan/atau kerugian pada Perusahaan melalui tindakan yang melanggar Syarat dan Ketentuan serta hukum dan/atau peraturan perundang-undangan yang berlaku,  Perusahaan dapat meminta ganti rugi atau kompensasi yang sepadan atas kerusakan dan/atau kerugian yang disebabkan oleh Anda termasuk mengambil langkah-langkah hukum terhadap Anda sebagai akibat dari tindakan yang dilakukakannya.
Sejauh diperkenankan oleh hukum dan/atau peraturan perundang-undangan yang berlaku, Anda setuju untuk membela, mengganti rugi dan membebaskan  Perusahaan pemegang saham, sponsor, mitra usaha Perusahaan, direktur, komisaris, karyawan dan agen, dari dan terhadap setiap dan semua klaim, kerusakan, kewajiban, kerugian, tanggung jawab, biaya atau hutang dan pengeluaran (termasuk, namun tidak terbatas pada, biaya penasihat hukum) yang timbul dari:
a.	Penggunaan atau akses Anda terhadap layanan Perusahaan yang disebabkan oleh kelalaian Anda
b.	Pelanggaran terhadap Syarat dan Ketentuan ini; dan/atau
c.	Pelanggaran oleh Anda terhadap pihak ketiga

11.	Keterkaitan Data Pribadi 
Anda setuju bahwa Perusahaan dapat memperoleh, menyimpan, mengelola, menggunakan dan mentransfer data pribadi Anda untuk tujuan menyediakan Layanan dalam Situs ini.
Pengelolaan data pribadi secara konsisten merujuk pada Kebijakan Privasi yang terpisah untuk detail pemrosesan data, yang merupakan praktik yang sangat baik dan yang ditentukan secara terpisah oleh Perusahaan.

12.	Keterpisahan
Apabila salah satu atau lebih ketentuan dalam Syarat dan Ketentuan ini dinyatakan tidak sah, batal atau tidak dapat diberlakukan, ketentuan tersebut harus dianggap terpisah dan tidak akan mempengaruhi validitas dan/atau keberlakukan ketentuan-ketentuan lainnya dalam Syarat dan Ketentuan yang tetap memiliki kekuatan hukum penuh dan berlaku.
 
13.	Keseluruhan Perjanjian
Syarat dan Ketentuan ini merupakan perjanjian yang lengkap dan eksklusif antara Anda dan Perusahaan sehubungan dengan Situs. Syarat dan Ketentuan ini menggantikan semua komunikasi, perjanjian, iklan, dan proposal sebelumnya atau saat ini, baik secara elektronik, lisan, atau tertulis, sehubungan dengan Situs ini atau versi lain dari program loyalitas pelanggan dari Perusahaan atau Pihak Perusahaan. Baik Anda maupun Perusahaan mengakui bahwa tidak satu pun dari Anda yang dibujuk untuk menyetujui Syarat dan Ketentuan ini oleh pernyataan atau janji apa pun yang tidak secara khusus dinyatakan dalam Syarat dan Ketentuan ini.
 
14.	Hukum yang Berlaku
Syarat dan Ketentuan ini diatur oleh Hukum Indonesia.
 
15.	Penyelesaian Perselisihan
Setiap perselisihan yang timbul dari atau berkenaan dengan Syarat dan Ketentuan ini, termasuk namun tidak terbatas pada setiap pertanyaan tentang keberadaan, validitas atau pengakhirannya, yang tidak dapat diselesaikan secara damai, harus diselesaikan melalui Pengadilan.

16.	Bahasa
Jika terdapat perbedaan atau ketidakkonsistenan antara versi bahasa Inggris dan versi bahasa Indonesia dari Syarat dan Ketentuan ini, versi bahasa Indonesia akan berlaku, mengatur, dan mengendalikan.

17.	Pemberitahuan
Anda setuju untuk menerima semua komunikasi termasuk pemberitahuan, perjanjian, pengungkapan, informasi lain dari Perusahaan atau dengan mengunggahnya pada fitur dalam Situs. Untuk pertanyaan terkait, Anda dapat menghubungi dan/atau menyampaikan pemberitahuan melalui alamat email: hrd.metland@metropolitanland.com.  

</p>

                    <form method="POST" action="{{ route('terms.accept') }}">
                        @csrf
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="acceptCheck" name="accept" required>
                            <label class="form-check-label" for="acceptCheck">Saya menyetujui bahwa data saya dikelola oleh Metropolitan Land Tbk sesuai syarat & ketentuan di atas.</label>
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn btn-outline-secondary">Keluar</a>
                            <button type="submit" class="btn btn-primary">Setujui & Lanjutkan</button>
                        </div>
                    </form>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
