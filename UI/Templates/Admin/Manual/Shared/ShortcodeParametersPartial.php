<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
?>
<h1>
    <span><?=esc_html($lang['LANG_MANUAL_SHORTCODE_PARAMETERS_TEXT']);?></span>
</h1>
<p>
    <strong>DISPLAY parameter values (required, case insensitive):</strong>
</p>
<ul>
    <li>
        display=&quot;deals&quot; - supports &quot;list&quot; value for &quot;LAYOUT&quot; parameter
    </li>
</ul>

<p>
    <strong>LAYOUT parameter values (optional, case insensitive):</strong>
</p>
<ul>
    <li>
        <em>(none)</em>
    </li>
    <li>
        layout=&quot;grid&quot;
    </li>
    <li>
        layout=&quot;list&quot;
    </li>
    <li>
        layout=&quot;slider&quot;
    </li>
    <li>
        layout=&quot;table&quot;
    </li>
    <li>
        layout=&quot;tabs&quot;
    </li>
</ul>

<p>
    <strong>STYLE parameter values (optional, case insensitive):</strong>
</p>
<ul>
    <li>
        <em>(none)</em>
    </li>
    <li>
        style=&quot;2&quot; - supports any positive integer number from 0 to maximum supported integer (&#39;PHP_INT_MAX&#39;)
    </li>
</ul>

<p>
    <strong>Additional parameters (optional, case insensitive):</strong>
</p>
<ul>
    <li>
        deal=&quot;1&quot; (default is all deals - &#39;-1&#39;)
    </li>
</ul>


<h3>Example:</h3>
<pre>
    [deals display=&quot;deals&quot; layout=&quot;slider&quot;]
    [deals display=&quot;deals&quot; deal=&quot;4&quot; layout=&quot;list&quot; style=&quot;2&quot;]
</pre>