<?php
    $out                               = array( );
    $close_date                        = strtotime( $_POST[ 'payoff_date' ] );
    $doy                               = ( date( 'z', $close_date ) + 0 ); // php is zero based
    $days                              = 365 + date( 'L', $close_date );
    $out[ 'days_in' ]                  = $doy;
    $out[ 'days_left' ]                = ( $days - $doy );
    $tax_rate                          = $_POST[ 'tax_rate' ];
    $tax_rate                          = ( $tax_rate / 100 );
    $out[ 'tax_rate' ]                 = $tax_rate;
    $_POST[ 'Purchase_Price' ]         = cleann( $_POST[ 'Purchase_Price' ] );
    $_POST[ 'Hazard_Insurance' ]       = cleann( $_POST[ 'Hazard_Insurance' ] );
    $_POST[ 'repairs' ]                = cleann( $_POST[ 'repairs' ] );
    $_POST[ 'warranty' ]               = cleann( $_POST[ 'warranty' ] );
	$_POST[ 'hoa_transfer' ]           = cleann( $_POST[ 'hoa_transfer' ] );
	$_POST[ 'attorney_fee' ]           = cleann( $_POST[ 'attorney_fee' ] );
	$_POST[ 'tax_certificate' ]        = cleann( $_POST[ 'tax_certificate' ] );
	$_POST[ 'escrow_fees' ]    		   = cleann( $_POST[ 'escrow_fees' ] );
    $_POST[ 'pest' ]                   = cleann( $_POST[ 'pest' ] );
    $_POST[ 'seller_credit_to_buyer' ] = cleann( $_POST[ 'seller_credit_to_buyer' ] );
	
	$_POST[ 'total_owners_premium_html' ] 	= preg_replace( "/[^0-9.]/", "", $_POST[ 'total_owners_premium_html' ] );
	$_POST[ 'seller_t1r_new_html' ] 		= preg_replace( "/[^0-9.]/", "", $_POST[ 'seller_t1r_new_html' ] );
	$_POST[ 'total_hoa_yearly_dues_html' ] 	= preg_replace( "/[^0-9.]/", "", $_POST[ 'total_hoa_yearly_dues_html' ] );
	
    $_POST[ 'split_precent_seller_c' ] = preg_replace( "/[^0-9.]/", "", $_POST[ 'split_precent_seller_c' ] );
    $_POST[ 'split_precent_buyer_c' ]  = preg_replace( "/[^0-9.]/", "", $_POST[ 'split_precent_buyer_c' ] );
    $out[ 'tax_paid' ]                 = ( $_POST[ 'Purchase_Price' ] * $tax_rate ); // paid for the year
    
    //was
    $out[ 'tax_paid' ]                 = ( $out[ 'tax_paid' ] * ( $doy / $days ) );
    //is
    //$out[ 'tax_paid' ]                 = ( $out[ 'tax_paid' ] * (($days - $doy ) / $days) );
	    
    $out[ 'total_loan_balancep' ]      = $_POST[ 'Purchase_Price' ];
    //    total_fixed_closeing
    $out[ 'total_fixed_closeing' ]     = 0;
    //$out[ 'total_fixed_closeing' ] += $_POST[ 'Hazard_Insurance' ];
    $out[ 'total_fixed_closeing' ] += $_POST[ 'repairs' ];
    $out[ 'total_fixed_closeing' ] += $_POST[ 'warranty' ];
	$out[ 'total_fixed_closeing' ] += $_POST[ 'hoa_transfer' ];
    $out[ 'total_fixed_closeing' ] += $_POST[ 'pest' ];
	$out[ 'total_fixed_closeing' ] += $_POST[ 'attorney_fee' ];
	$out[ 'total_fixed_closeing' ] += $_POST[ 'tax_certificate' ];
    $out[ 'total_fixed_closeing' ] += $_POST[ 'seller_credit_to_buyer' ];
	$out[ 'total_fixed_closeing' ] += $_POST[ 'escrow_fees' ];
    $out[ 'total_fixed_closeing' ] += $_POST[ 'split_precent_seller_c' ];
    $out[ 'total_fixed_closeing' ] += $_POST[ 'split_precent_buyer_c' ];
    for ( $kk = 1; $kk <= $_POST[ 'n_expensess' ]; $kk++ ) {
        $out[ 'total_fixed_closeing' ] += $_POST[ 'n_expense_c' . $kk ];
    }
        
    if($_POST[ 'owners_premium_paid_by' ] == "Buyer"){
        //Button Buyer was pressed
		$out[ 'total_fixed_closeing' ] = $out[ 'total_fixed_closeing' ] - $_POST[ 'total_hoa_yearly_dues_html' ];
        
    }else{
        //Button Seller was Pressed
		$out[ 'total_fixed_closeing' ] = $out[ 'total_fixed_closeing' ] + $_POST[ 'total_owners_premium_html' ] - $_POST[ 'total_hoa_yearly_dues_html' ];
    }

    if($_POST[ 'seller_t1r_paid_by' ] == "Seller"){
        //Button Seller was Pressed
		$out[ 'total_fixed_closeing' ] = $out[ 'total_fixed_closeing' ] + $_POST[ 'seller_t1r_new_html' ];
    }
	
    //loans
    $loans = 0;
    for ( $kk = 1; $kk <= $_POST[ 'n_loans' ]; $kk++ ) {
        $loans += floatval( str_replace( ',', '', $_POST[ 'nloannn_' . $kk ] ) );
    }
    $out[ 'total_sale_price' ]      = '$' . number_format( $_POST[ 'Purchase_Price' ], 2 );
    $out[ 'total_loan_balance' ]    = '$' . number_format( $loans, 2 );
    $out[ 'total_loan_balancep' ]   = '$' . number_format( $loans, 2 );
    //balance_due_at_close
    $out[ 'balance_due_at_close' ]  = 0;
    $out[ 'balance_due_at_close' ]  = $_POST[ 'Purchase_Price' ] - $loans - $out[ 'tax_paid' ] - $out[ 'total_fixed_closeing' ];
    $out[ 'balance_due_at_closea' ] = array(
         $_POST[ 'Purchase_Price' ],
        ( $loans * -1 ),
        ( $out[ 'tax_paid' ] * -1 ),
        ( $out[ 'total_fixed_closeing' ] * -1 ) 
    );
    $out[ 'loans' ]                 = $loans;
    $out[ 'total_fixed_closeing' ]  = '$' . number_format( $out[ 'total_fixed_closeing' ], 2 );
    $out[ 'tax_paid' ]              = '$' . number_format( $out[ 'tax_paid' ], 2 );
    $out[ 'balance_due_at_close' ]  = '$' . number_format( $out[ 'balance_due_at_close' ], 2 );
    //     $out[ 'total_loan_balancep' ] = '$' . number_format( $out[ 'total_loan_balancep' ], 2 );
    echo json_encode( $out, true );
    function cleann( $s ) {
        return floatval( str_replace( ',', '', $s ) );
    }
?>