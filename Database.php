<?php

$conn = new mysqli( "localhost", "root", "", "latihan" );
$date = date( 'Y-m-d' );
if( isset($_GET['action'] ) )
{
    if( $_GET['action'] == 'check' )
    {
        if( !empty($_GET['target'] ))
        {
            // var_dump( "SELECT book_time FROM book WHERE ( tanggal LIKE '%$date%' AND produk_id = '$_GET[target]' )" );die;
            $hasil = $conn->query( "SELECT book_time FROM book WHERE ( tanggal LIKE '%$date%' AND produk_id = '$_GET[target]' )" );
            if( $hasil->num_rows > 0 )
            {
                while( $p = $hasil->fetch_assoc() )
                {
                    $check['waktu'] = $p['book_time'];
                    $checked[] = $check;
                }
                echo json_encode( [ "results" => $checked] );
            } else {
                echo json_encode( [ "results" => null ] );
            }
        } else {
            echo json_encode( [ "results" => null ] );
        }
    } elseif( $_GET['action'] == 'input' )
    {
        $inputan = file_get_contents( "php://input" );
        $data = json_decode( $inputan, true );
        // var_dump($data['produkID']); die;
        if( !empty($data['produkID'] ))
        {
            if( !empty( $data['produkWaktu'] ))
            {
                $hasil = mysqli_query( $conn, "INSERT INTO book ( produk_id, book_time ) VALUE ( '$data[produkID]', '$data[produkWaktu]' )" );
                $alert['alert'] = 'success';
                $alert['text'] = 'Penyewaan ' . fasilitas($data['produkID']) . ', pada jam ' . waktu( $data['produkWaktu'] ) . 'telah di terima' ;
                echo json_encode( $alert );
            } else {
                $alert['alert'] = 'danger';
                $alert['text'] = 'Tolong isi form dengan benar' ;
                echo json_encode( $alert );
            }
        } else {
            $alert['alert'] = 'danger';
            $alert['text'] = 'Tolong isi form dengan benar' ;
            echo json_encode( $alert );
        }

    }
}

function fasilitas( $xxx )
{
    $name = [
    "1" =>"Gedung Serba Guna",
    "2" =>"Masjid",
    "3" =>"Gereja",
    "4" =>"Kelenteng",
    "5" =>"Vihara",
    "6" =>"Pura",
    "7" =>"Lapangan Futsal",
    "8" =>"Lapangan Sepak Bola",
    "9" =>"Lapangan Volly",
    "10" =>"Lapangan Batminton",
    "11" =>"Lapangan Sepak Takrow",
    "12" =>"Rumah Prasmanan"
    ];
    return $name[ $xxx ];
}
function waktu( $ppp )
{
    $time = [
        "1" => "08:00 - 11:30",
        "2" => "12:30 - 16:00",
        "3" => "17:00 - 21:00"
    ];
    return $time[ $ppp ];
}