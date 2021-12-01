<?php


/**************************************************************
Call: insert_word_ignore.php?tid=[textid]&ord=[textpos]
Ignore single word (new term with status 98)
 ***************************************************************/

require_once 'inc/session_utility.php';

$word = get_first_value("select Ti2Text as value from " . $tbpref . "textitems2 where Ti2WordCount = 1 and Ti2TxID = " . $_REQUEST['tid'] . " and Ti2Order = " . $_REQUEST['ord']);

$wordlc =    mb_strtolower($word, 'UTF-8');

$langid = get_first_value("select TxLgID as value from " . $tbpref . "texts where TxID = " . $_REQUEST['tid']);

pagestart("Term: " . $word, false);

$m1 = runsql(
    'insert into ' . $tbpref . 'words (WoLgID, WoText, WoTextLC, WoStatus, WoWordCount, WoStatusChanged,' .  make_score_random_insert_update('iv') . ') values( ' . 
    $langid . ', ' . 
    convert_string_to_sqlsyntax($word) . ', ' . 
    convert_string_to_sqlsyntax($wordlc) . ', 98, 1, NOW(), ' .  
    make_score_random_insert_update('id') . ')', 'Term added'
);
$wid = get_last_key();
do_mysqli_query("UPDATE  " . $tbpref . "textitems2 SET Ti2WoID  = " . $wid . " where Ti2LgID = " . $langid . " and lower(Ti2Text) = " . convert_string_to_sqlsyntax($wordlc));
echo "<p>OK, this term will be ignored!</p>";

$hex = strToClassName($wordlc);

?>
<script type="text/javascript">
//<![CDATA[
var context = window.parent.frames['l'].document;
var contexth = window.parent.frames['h'].document;
var title = make_tooltip(<?php echo prepare_textdata_js($word); ?>,'*','','98');
$('.TERM<?php echo $hex; ?>', context).removeClass('status0').addClass('status98 word<?php echo $wid; ?>').attr('data_status','98').attr('data_wid','<?php echo $wid; ?>').attr('title',title);
$('#learnstatus', contexth).html('<?php echo addslashes(texttodocount2($_REQUEST['tid'])); ?>');
window.parent.frames['l'].focus();
window.parent.frames['l'].setTimeout('cClick()', 100);
//]]>
</script>
<?php

pageend();

?>
