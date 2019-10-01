<?php
    //clean up old ones
    $dir     = "../files/";
    $max_age = 3;
    $now     = date( 'U' );
    // Open a known directory, and proceed to read its contents
    if ( is_dir( $dir ) ) {
        if ( $dh = opendir( $dir ) ) {
            while ( ( $file = readdir( $dh ) ) !== false ) {
                $fss = $dir . $file;
                if ( substr( $fss, -5 ) == '_.pdf' ) {
                    //echo "\n" . substr( $fss, -5 );
                    $ftt      = filectime( $fss );
                    $ftt_days = round( ( ( $now - $ftt ) / 86400 ), 8 );
                    //echo " " . filectime( $fss ) . " ? ? " . $max_age . " !! " .$ftt_days;
                    if ( $ftt_days > $max_age ) {
                        //echo "\n killing " . $fss;
                        unlink( $fss );
                    }
                }
            }
            closedir( $dh );
        }
    }
    #####
    $raw       = file_get_contents( '../seller_net_sheet_print.html' );
    $loans     = 0;
    //1:m loans
    $nloannn_x = '';
    for ( $kk = 1; $kk <= $_POST[ 'n_loans' ]; $kk++ ) {
        $nloannn_x .= '
<tr>
<td>Loan ' . $kk . '</td>
<td align="right">$' . @number_format( preg_replace( "/[^0-9.]/", "", $_POST[ 'nloannn_' . $kk ] ), 2 ) . '</td>
</tr>
';
        $loans += $_POST[ 'nloannn_' . $kk ];
    }
    $_POST[ 'nloannn_x' ] = $nloannn_x;
    //1:m expenses
    $n_expensess          = '';
    for ( $kk = 1; $kk <= $_POST[ 'n_expensess' ]; $kk++ ) {
        $n_expensess .= '
<tr>
<td>' . $_POST[ 'n_expense_' . $kk ] . '</td>
<td align="right">$' . number_format( $_POST[ 'n_expense_c' . $kk ], 2 ) . '</td>
</tr>
';
    }
    $n_expensess .= '';
    $_POST[ 'n_expense_h' ] = $n_expensess;
    if ( !isset( $_POST[ 'purchase_type_seller' ] ) ) {
        $_POST[ 'purchase_type_seller' ] = '';
    }
    $cash_or_mortgage = $_POST[ 'purchase_type_seller' ];
    if ( $cash_or_mortgage == "cash" ) {
        $_POST[ 'Mortgage_type' ]        = '';
        $_POST[ 'purchase_type_seller' ] = 'Cash';
    }
    $_POST[ 'total_fixed_closeing' ]          = $_POST[ 'total_fixed_closeingp' ];
    $_POST[ 'property_taxes_owed_by_seller' ] = $_POST[ 'property_taxes_owed_by_sellerp' ];
    $_POST[ 'balance_due_at_close' ]          = $_POST[ 'balance_due_at_closep' ];
    $_POST[ 'total_other_expenses' ]          = $_POST[ 'total_other_expensesp' ];
    $_POST[ 'total_loan_balance' ]            = $_POST[ 'total_loan_balancep' ];
    //  $raw .= "<pre>" . print_r( $_POST, 1 ) . "</pre>";
    $html                                     = smart_template( $raw, $_POST );
    if ( $_GET[ 'mode' ] == 'print' ) {
        echo htmlspecialchars( $html );
    } elseif ( $_GET[ 'mode' ] == 'pdf' ) {
        //do pdf stuff
        echo as_pdf( $html );
    }
    function smart_template( $s, $ar, $looped = false ) {
        foreach ( $ar as $kk => $vv ) {
            //            echo "\n<br>:".$kk.":".$vv;
            if ( is_array( $vv ) ) {
                $vv = implode( '', $vv );
            }
            $s = str_replace( "{" . $kk . "}", $vv, $s );
        }
        return $s;
    }
    function as_pdf( $dataText ) {
        /*
        $dataText .= '<style>' . file_get_contents( _BASE_PATH . '/../styles_pdf.css' ) . '</style>';
        $dataText = str_ireplace( ' width="1600px"', ' width="780px" ', $dataText );
        $dataText = str_ireplace( ' width="1600"', ' width="780px" ', $dataText );
        $dataText = str_ireplace( '<table', "\n<br>\n<table ", $dataText );
        */
        //die($dataText);
        //    $dataText = mt_rand(100000,900000).'<hr>'.$dataText.'<hr>'.mt_rand(100000,900000).'<hr>'.date('r');
        require_once( __DIR__ . '/../tcpdf/tcpdf.php' );
        // create new PDF document
        $pdf = new TCPDF( 'P', PDF_UNIT, 'Letter', true, 'UTF-8', false );
        // set default monospaced font
        //        $pdf->SetDefaultMonospacedFont( PDF_FONT_MONOSPACED );
        $pdf->setPrintHeader( false );
        $pdf->setPrintFooter( false );
        //        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetMargins( 7, 3, 7, true );
        $pdf->SetAutoPageBreak( TRUE, 6 );
        $pdf->SetCellPadding( 0.25 );
        $pdf->setImageScale( 1.53 );
        $pdf->SetFont( 'Helvetica', '', 9 );
        // set div margins
        $tagvs = array(
             'div' => array(
                 0 => array(
                     'h' => 0.000000001,
                    'n' => 1 
                ),
                1 => array(
                     'h' => 0.000000001,
                    'n' => 1 
                ) 
            ),
            'hr' => array(
                 0 => array(
                     'h' => 0.000000001,
                    'n' => 1 
                ),
                1 => array(
                     'h' => 0.000000001,
                    'n' => 1 
                ) 
            ),
            'aside' => array(
                 0 => array(
                     'h' => 0.000000001,
                    'n' => 1 
                ),
                1 => array(
                     'h' => 0.000000001,
                    'n' => 1 
                ) 
            ) 
        );
        $pdf->setHtmlVSpace( $tagvs );
        // add a page (set all paramaters before this)
        $pdf->AddPage();
        // output the HTML content        
        $pdf->writeHTML( $dataText, true, false, true, false, '' );
        // reset pointer to the last page
        //        $pdf->lastPage();
        //Close and output PDF document
        $ff = 'seller_net_sheet_' . date( 'U' ) . '_.pdf';
        $pdf->Output( __DIR__ . '/../files/' . $ff, 'F' );
        return json_encode($ff,true);
    }
?>