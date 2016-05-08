<?php
file_put_contents(get_ini('UPLOAD_FOLDER').'queue/'.$job->pluginName.'_'.$job->page.'.xml',
utf8_encode(trim('
<?xml version="1.0" encoding="UTF-8"?>
<XML>
<HEADER>
<PPID>1</PPID>
<DATE>'.date('Y-m-d H:i:s').'</DATE>
<TIMEOUT>120</TIMEOUT>
<PLUGIN>core</PLUGIN>
<PAGE>task_translate</PAGE>
<COMMENT><![CDATA[Translate .po files.]]></COMMENT>
</HEADER>
<DATA>
</DATA>
</XML>
'))
);
?>