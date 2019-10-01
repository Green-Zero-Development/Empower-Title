<?php
    //clean up old ones
    error_reporting( 0 );
    $dir     = "../files/";
    $max_age = 3;
    $now     = date( 'U' );
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
    $raw       = file_get_contents( '../buyer_cost_sheet_print.html' );
    $loans     = 0;
    //1:m loans
    $nloannn_x = '';
    $nloans_am = 0;
    for ( $kk = 1; $kk <= $_POST[ 'n_loans' ]; $kk++ ) {
        $nn_ = preg_replace( "/[^0-9.]/", "", $_POST[ 'nloannn_' . $kk ] );
        $nloans_am += $nn_;
        $nloannn_x .= '
<tr>
<td>Loan ' . $kk . '</td>
<td align="right">$' . @number_format( $nn_, 2 ) . '</td>
</tr>
';
        $loans += $_POST[ 'nloannn_' . $kk ];
    }
    $_POST[ 'nloannn_x' ] = $nloannn_x;
    //1:m expenses
    $n_expensess          = '';
    for ( $kk = 1; $kk <= $_POST[ 'n_bexpensess' ]; $kk++ ) {
        $n_expensess .= '
<tr>
<td>' . $_POST[ 'n_bexpense_' . $kk ] . '</td>
<td align="right">$' . number_format( $_POST[ 'n_bexpense_c' . $kk ], 2 ) . '</td>
</tr>
';
    }
    $n_expensess .= '';
    $_POST[ 'n_bexpense_h' ] = $n_expensess;
    if ( !isset( $_POST[ 'purchase_type_seller' ] ) ) {
        $_POST[ 'purchase_type_seller' ] = '';
    }
    $cash_or_mortgage = $_POST[ 'purchase_type_buyer' ];
    if ( $cash_or_mortgage == "cash" ) {
        $_POST[ 'buyer_Mortgage_type' ]        = '';
        $_POST[ 'buyer_purchase_type_seller' ] = 'Cash';
    } else {
        $_POST[ 'buyer_Mortgage_type' ]        = '';
        $_POST[ 'buyer_purchase_type_seller' ] = 'Mortgage';
    }
    $non_zeros    = array( );
    $non_zeros[ ] = 'total_other_expensesp';
    $non_zeros[ ] = 'buyer_down_payment';
    $non_zeros[ ] = 'buyer_taxrate';
    $non_zeros[ ] = 'buyer_hazard';
    $non_zeros[ ] = 'buyer_repairs';
    $non_zeros[ ] = 'buyer_waranty';
    $non_zeros[ ] = 'buyer_pest';
    $non_zeros[ ] = 'buyer_seller_credit';
    $non_zeros[ ] = 'buyer_purchase_rpicep';
    $non_zeros[ ] = 'buyer_total_fixed';
    $non_zeros[ ] = 'buyer_tax_owed';
    $non_zeros[ ] = 'buyer_balance';
    $non_zeros[ ] = 'total_other_expensesp';
    $non_zeros[ ] = 'total_fixed_closeing';
    $non_zeros[ ] = 'property_taxes_owed_by_seller';
    $non_zeros[ ] = 'balance_due_at_close';
    $non_zeros[ ] = 'total_other_expenses';
    $non_zeros[ ] = 'total_loan_balance';
    $non_zeros[ ] = 'buyer_taxrate_cashc';
    $non_zeros[ ] = 'buyer_purchasep';
    $non_zeros[ ] = 'total_loan_balancep';
    $non_zeros[ ] = 'balance_due_at_closep';
    foreach ( $non_zeros as $kkv ) {
        if ( !isset( $_POST[ $kkv ] ) ) {
            $_POST[ $kkv ] = 0;
        }
        if ( $_POST[ $kkv ] == '' ) {
            $_POST[ $kkv ] = 0;
        }
        if ( !is_numeric( $_POST[ $kkv ] ) ) {
            $_POST[ $kkv ] = 0;
        }
    }
    $paying_ = preg_replace( "/[^0-9.]/", "", $_POST[ 'buyer_purchase' ] );
    $down_   = preg_replace( "/[^0-9.]/", "", $_POST[ 'buyer_down_payment' ] );
    if ( !is_numeric( $paying_ ) ) {
        $paying_ = 0;
    }
    if ( !is_numeric( $down_ ) ) {
        $down_ = 0;
    }
    $_POST[ 'buyer_total_loan_balancep' ]     = @number_format( ( $paying_ - $down_ ) );
    $_POST[ 'total_fixed_closeing' ]          = $_POST[ 'total_fixed_closeingp' ];
    $_POST[ 'property_taxes_owed_by_seller' ] = $_POST[ 'property_taxes_owed_by_sellerp' ];
    $_POST[ 'balance_due_at_close' ]          = $_POST[ 'balance_due_at_closep' ];
    $_POST[ 'total_other_expenses' ]          = $_POST[ 'total_other_expensesp' ];
    $_POST[ 'total_loan_balance' ]            = $_POST[ 'total_loan_balancep' ];
    $_POST[ 'buyer_taxrate_cashc' ]           = @number_format( $_POST[ 'buyer_taxrate_cash_raw' ], 2 );
    $_POST[ 'buyer_purchasep' ]               = @number_format( $_POST[ 'buyer_purchase' ], 2 );
    $_POST[ 'buyer_total_fixed' ]             = @number_format( $_POST[ 'buyer_total_fixed' ], 2 );
    $_POST[ 'buyer_balance_due_at_close' ]    = @number_format( $_POST[ 'buyer_balance' ], 2 );
    //$raw .= "<pre>" . print_r( $_POST, 1 ) . "</pre>";
    $html = smart_template( $raw, $_POST );
    if ( $_GET[ 'mode' ] == 'print' ) {
        //        echo htmlspecialchars( $html );
        echo $html;
        echo "<pre>" . print_r( $_POST, true ) . "</pre>";
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
        $ff = 'buyer_cost_sheet_' . date( 'U' ) . '_.pdf';
        $pdf->Output( __DIR__ . '/../files/' . $ff, 'F' );
        return $ff;
    }
?>