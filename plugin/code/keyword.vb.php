<?php
/**
 *�����������ե�����
 */

$switchHash["\""] = NONESCAPE_LITERAL;  // VisualBasic�Ǥ� \ ��ʸ��
$mkoutline = $option["outline"] = false; // �����ȥ饤��⡼���Բ� 

// ���������
$switchHash["'"] = COMMENT;    // �����Ȥ� ' ������Ԥޤ�
$switchHash["r"] = COMMENT_WORD;   // �����Ȥ� REM ������Ԥޤ�
$switchHash["R"] = COMMENT_WORD;
$code_comment = Array(
	"'" => Array(
		"/^'.*\n/",
		),
	"r" => Array(
		"/^rem\s.*\n/i",
		),
	"R" => Array(
		"/^rem\s.*\n/i",
		),
);

$code_css = Array(
  'operator',		// ���ڥ졼���ؿ�
  'identifier',	// ����¾�μ��̻�
  'pragma',		// module, import �� pragma
  'system',		// �������Ȥ߹��ߤ��� __stdcall �Ȥ�
  );

$code_keyword = Array(
	'AddressOf' => 2,
	'Alias' => 2,
	'And' => 2,
	'Any' => 2,
	'As' => 2,
	'Base' => 2,
	'Binary' => 2,
	'Boolean' => 2,
	'Begin' => 2,
	'ByRef' => 2,
	'Byte' => 2,
	'ByVal' => 2,
	'Call' => 2,
	'Case' => 2,
	'CBool' => 2,
	'CByte' => 2,
	'CCur' => 2,
	'CDate' => 2,
	'CDbl' => 2,
	'CInt' => 2,
	'CLng' => 2,
	'Close' => 2,
	'Compare' => 2,
	'Const' => 2,
	'CSng' => 2,
	'CStr' => 2,
	'Currency' => 2,
	'CVar' => 2,
	'CVErr' => 2,
	'Database' => 2,
	'Date' => 2,
	'Debug' => 2,
	'Declare' => 2,
	'DefBool' => 2,
	'DefByte' => 2,
	'DefCur' => 2,
	'DefDate' => 2,
	'DefDbl' => 2,
	'DefInt' => 2,
	'DefLng' => 2,
	'DefObj' => 2,
	'DefSng' => 2,
	'DefStr' => 2,
	'DefVar' => 2,
	'Dim' => 2,
	'Do' => 2,
	'Double' => 2,
	'Each' => 2,
	'Else' => 2,
	'End' => 2,
	'Enum' => 2,
	'Eqv' => 2,
	'Erase' => 2,
	'Error' => 2,
	'Event' => 2,
	'Exit' => 2,
	'Explicit' => 2,
	'For' => 2,
	'Friend' => 2,
	'Function' => 2,
	'Get' => 2,
	'Global' => 2,
	'GoSub' => 2,
	'GoTo' => 2,
	'If' => 2,
	'Imp' => 2,
	'Implements' => 2,
	'In' => 2,
	'Input' => 2,
	'Integer' => 2,
	'Is' => 2,
	'LBound' => 2,
	'Len' => 2,
	'Let' => 2,
	'Lib' => 2,
	'Like' => 2,
	'Line' => 2,
	'Lock' => 2,
	'Long' => 2,
	'Loop' => 2,
	'LSet' => 2,
	'Mod' => 2,
	'Module' => 2,
	'Name' => 2,
	'Next' => 2,
	'Not' => 2,
	'Nothing' => 2,
	'Null' => 2,
	'Object' => 2,
	'On' => 2,
	'Open' => 2,
	'Option' => 2,
	'Optional' => 2,
	'Or' => 2,
	'Output' => 2,
	'ParamArray' => 2,
	'Preserve' => 2,
	'Print' => 2,
	'Private' => 2,
	'Property' => 2,
	'Public' => 2,
	'Put' => 2,
	'RaiseEvent' => 2,
	'Random' => 2,
	'Read' => 2,
	'ReDim' => 2,
	'Resume' => 2,
	'Return' => 2,
	'RSet' => 2,
	'Seek' => 2,
	'Select' => 2,
	'Set' => 2,
	'Single' => 2,
	'Spc' => 2,
	'Static' => 2,
	'Step' => 2,
	'Stop' => 2,
	'String' => 2,
	'Sub' => 2,
	'Tab' => 2,
	'Text' => 2,
	'Then' => 2,
	'To' => 2,
	'Type' => 2,
	'UBound' => 2,
	'Unlock' => 2,
	'Variant' => 2,
	'Wend' => 2,
	'While' => 2,
	'With' => 2,
	'WithEvents' => 2,
	'Write' => 2,
	'Xor' => 2,

  );
?>