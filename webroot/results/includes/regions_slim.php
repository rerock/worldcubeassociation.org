<?php

showRegionalRecordsSlim();

#----------------------------------------------------------------------
function showRegionalRecordsSlim () {
#----------------------------------------------------------------------
  global $chosenYears;

  require( 'regions_get_current_records.php' );

  tableBegin( 'results', 6 );

  $caption = spaced( array( chosenRegionName(), $chosenYears ));
  if( $caption ) tableCaption( true, $caption );
  else tableRowBlank();

  tableHeader( explode( '|', 'Person|Single|Event|Average|Person|Result Details' ),
               array( 1 => "class='R2'", 2 => "class='c'", 3 => "class='R2'", 5 => 'class="f"' ));

  #--- Process events.
  foreach( structureBy( $results, 'eventId' ) as $eventResults ){
    $structure = structureBy( $eventResults, 'type' );
    $singles = $structure[0];
    $averages = isset( $structure[1] ) ? $structure[1] : array();
    $wasShownSinglePerson = $wasShownAveragePerson = array();

    #--- Process records for this event.
    $first = true;
    while( $singles || $averages ){

      #--- Get next single.
      $s = array_shift( $singles );
      if( isset( $wasShownSinglePerson[$s['personId']] )) $s = false;
      $wasShownSinglePerson[$s['personId']] = true;

      #--- Get next average.
      $a = array_shift( $averages );
      if( isset( $wasShownAveragePerson[$a['personId']] )) $a = false;
      $wasShownAveragePerson[$a['personId']] = true;

      if( $s || $a ){
        tableRow( array(
          $s ? personLink( $s['personId'], $s['personName'] ) : '',
          $first ? formatValue( $s['value'], $s['format'] ) : '',
          $first ? eventLink( $s['eventId'], $s['eventCellName'] ) : '',
          $first ? formatValue( $a['value'], $a['format'] ) : '',
          $a ? personLink( $a['personId'], $a['personName'] ) : '',
         $a ? formatAverageSources( true, $a, $a['format'] ) : ''
        ));
      }

      $first = false;
    }
  }

  tableEnd();
}

?>
