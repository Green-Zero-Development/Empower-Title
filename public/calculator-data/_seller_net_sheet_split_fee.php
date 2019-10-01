<?php
    $out            = array( );
    $out[ 'error' ] = '';
    $Purchase_Price = preg_replace( "/[^0-9.]/", "", $_POST[ 'Purchase_Price' ] );
    if ( ( $Purchase_Price <= 0 ) || ( $Purchase_Price == '' ) ) {
        $out[ 'error' ] = 'A Purchase Price is required for a valid fee calculation.';
    }
    $total_broker_fee = preg_replace( "/[^0-9.]/", "", $_POST[ 'total_broker_fee' ] );
    if ( isset( $_GET[ 'mode' ] ) ) {
        $mode = $_GET[ 'mode' ];
    } else {
        $mode = null;
    }
    if ( $total_broker_fee > 100 ) {
        //assume $s
        $out[ 'total_broker_feec' ] = '$' . number_format( $total_broker_fee, 2 );
        $out[ 'total_broker_fee' ]  = '$' . number_format( $total_broker_fee, 2 );
        if ( $mode == 'seller' ) {
            $seller_gets = preg_replace( "/[^0-9.]/", "", $_POST[ 'split_precent_seller' ] );
            if ( $seller_gets > $total_broker_fee ) {
                //no!
                $split                           = '$' . number_format( ( $total_broker_fee / 2 ), 2 );
                $out[ 'split_precent_seller' ]   = $split;
                $out[ 'split_precent_seller_c' ] = $split;
                $out[ 'split_precent_buyer' ]    = $split;
                $out[ 'split_precent_buyer_c' ]  = $split;
            } else {
                $buyer_gets                      = '$' . number_format( ( $total_broker_fee - $seller_gets ), 2 );
                $out[ 'split_precent_seller' ]   = '$' . number_format( $seller_gets, 2 );
                $out[ 'split_precent_seller_c' ] = '$' . number_format( $seller_gets, 2 );
                $out[ 'split_precent_buyer' ]    = $buyer_gets;
                $out[ 'split_precent_buyer_c' ]  = $buyer_gets;
            }
            $split = '$' . number_format( ( $total_broker_fee / 2 ), 2 );
        } elseif ( $mode == 'buyer' ) {
            $buyer_gets = preg_replace( "/[^0-9.]/", "", $_POST[ 'split_precent_buyer' ] );
            if ( $buyer_gets > $total_broker_fee ) {
                //no!
                $split                           = '$' . number_format( ( $total_broker_fee / 2 ), 2 );
                $out[ 'split_precent_seller' ]   = $split;
                $out[ 'split_precent_seller_c' ] = $split;
                $out[ 'split_precent_buyer' ]    = $split;
                $out[ 'split_precent_buyer_c' ]  = $split;
            } else {
                $seller_gets                     = '$' . number_format( ( $total_broker_fee - $buyer_gets ), 2 );
                $out[ 'split_precent_seller' ]   = $seller_gets;
                $out[ 'split_precent_seller_c' ] = $seller_gets;
                $out[ 'split_precent_buyer' ]    = '$' . number_format( $buyer_gets, 2 );
                $out[ 'split_precent_buyer_c' ]  = '$' . number_format( $buyer_gets, 2 );
            }
        } else {
            //split it 50/50
            $split                           = '$' . number_format( ( $total_broker_fee / 2 ), 2 );
            $out[ 'split_precent_seller' ]   = $split;
            $out[ 'split_precent_seller_c' ] = $split;
            $out[ 'split_precent_buyer' ]    = $split;
            $out[ 'split_precent_buyer_c' ]  = $split;
        }
    } else {
        //assume %
        $out[ 'total_broker_feec' ] = ( $Purchase_Price * ( $total_broker_fee / 100 ) );
        $out[ 'total_broker_fee' ]  = number_format( $total_broker_fee, 3 ) . '%';
        if ( $mode == 'seller' ) {
            $seller_gets = preg_replace( "/[^0-9.]/", "", $_POST[ 'split_precent_seller' ] );
            if ( $seller_gets > $total_broker_fee ) {
                //no!
                $split                           = number_format( ( $total_broker_fee / 2 ), 3 ) . '%';
                $split_c                         = '$' . number_format( ( $out[ 'total_broker_feec' ] / 2 ), 2 );
                $out[ 'split_precent_seller' ]   = $split;
                $out[ 'split_precent_seller_c' ] = $split_c;
                $out[ 'split_precent_buyer' ]    = $split;
                $out[ 'split_precent_buyer_c' ]  = $split_c;
            } else {
                $seller_gets = round($seller_gets,3);
                $buyer_gets                      = ( $total_broker_fee - $seller_gets );
                $out[ 'split_precent_seller' ]   = number_format( $seller_gets, 3 ) . '%';
                $out[ 'split_precent_seller_c' ] = '$' . number_format( $Purchase_Price * ( $seller_gets / 100 ), 2 );
                $out[ 'split_precent_buyer' ]    = number_format( $buyer_gets, 3 ) . '%';
                $out[ 'split_precent_buyer_c' ]  = '$' . number_format( $Purchase_Price * ( $buyer_gets / 100 ), 2 );
            }
        } elseif ( $mode == 'buyer' ) {
            $buyer_gets = preg_replace( "/[^0-9.]/", "", $_POST[ 'split_precent_buyer' ] );
            if ( $buyer_gets > $total_broker_fee ) {
                //no!
                $split                           = number_format( ( $total_broker_fee / 2 ), 3 ) . '%';
                $split_c                         = '$' . number_format( ( $out[ 'total_broker_feec' ] / 2 ), 2 );
                $out[ 'split_precent_seller' ]   = $split;
                $out[ 'split_precent_seller_c' ] = $split_c;
                $out[ 'split_precent_buyer' ]    = $split;
                $out[ 'split_precent_buyer_c' ]  = $split_c;
            } else {
                   $buyer_gets = round($buyer_gets,3);
                $seller_gets                     = ( $total_broker_fee - $buyer_gets );
                $out[ 'split_precent_seller' ]   = number_format( $seller_gets, 3 ) . '%';
                $out[ 'split_precent_seller_c' ] = '$' . number_format( $Purchase_Price * ( $seller_gets / 100 ), 2 );
                $out[ 'split_precent_buyer' ]    = number_format( $buyer_gets, 3 ) . '%';
                $out[ 'split_precent_buyer_c' ]  = '$' . number_format( $Purchase_Price * ( $buyer_gets / 100 ), 2 );
                ;
            }
        } else {
            $split                           = number_format( ( $total_broker_fee / 2 ), 3 ) . '%';
            $out[ 'split_precent_seller' ]   = $split;
            $out[ 'split_precent_buyer' ]    = $split;
            $split_c                         = '$' . number_format( ( $out[ 'total_broker_feec' ] / 2 ), 2 );
            $out[ 'split_precent_seller_c' ] = $split_c;
            $out[ 'split_precent_buyer_c' ]  = $split_c;
        }
        $out[ 'total_broker_feec' ] = '$' . number_format( $out[ 'total_broker_feec' ], 2 );
    }
    echo json_encode( $out, true );
?>