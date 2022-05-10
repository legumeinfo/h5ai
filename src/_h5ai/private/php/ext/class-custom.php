<?php

class Custom {
    private $context;

    public function __construct($context) {
        $this->context = $context;
    }

    public function get_customizations($href) {
        if (!$this->context->query_option('custom.enabled', false)) {
            return [
                'header' => ['content' => null, 'type' => null],
                'footer' => ['content' => null, 'type' => null]
            ];
        }

        $path = $this->context->to_path($href);

        $header = null;
        $header_type = null;
        $footer_type = 'html';
        $footer = null;

        foreach (glob($path . "/README.*.yml") as $readme_path) {
            if (is_readable($readme_path)) {
                $content = file_get_contents($readme_path);
                $footer .= '<code style="white-space: pre-wrap">' . file_get_contents($readme_path) . '</code>';
                break;
            }
        }

        $footer .='
<hr>
<p>
<b>Abbreviations</b>:
<b>gnm</b> = genome assembly; 
<b>ann</b> = annotation; 
<b>bac</b> = BAC library;   
<b>map</b> = map; 
<b>mrk</b> = markers; 
<b>div</b> = diversity; 
<b>gws</b> = GWAS; 
<b>fam</b> = gene family;   
<b>rpt</b> = repeats; 
<b>tcp</b> = transcriptome;
<b>trt</b> = traits;
<b>syn</b> = synteny; 
<b>.gzi, .fai</b> = index files produced by samtools fadix
</p> <br>
<p><b>Navigating</b>: Navigate via the folder links below or to the left.</p>
<p><b>Downloading</b>: Mark one or more folders or files with a checkmark (hover to the left of the folder icon), then click the download icon (down-arrow in the upper left).</p>
<p><b>Searching</b>: See the magnifying glass in the upper left.</p>
<p><b>Dataset key names and Registry</b>: The four-letter string in the README and the filenames (e.g. gNmT) is a unique key, which associates the file(s) in a directory (a data collection) with the metadata for the file(s). The keys are also recorded at the <a href="http://bit.ly/LegFed_registry">Registry</a>.</p>
<br>
<p><b>Please <a href="/contact">contact us</a></b> to contribute data, or to set up a similar repository. 
Metadata templates and protocols: see the <a href="/data/metadata/">metadata directory</a> and 
<a href="https://github.com/LegumeFederation/datastore/">github</a>.</p>';

        if ($header === null) {
            foreach (glob($path . "/*/README.*.yml") as $readme_path) {
                $header ??= "<h3>Overview of data in this directory</h3>";
                $header_type ??= "html";
                $header .= "<b>" . basename(dirname($readme_path)) . "</b>: "
                        . exec("sed -n -e 's/\"//g' -e 's/^synopsis: //p' '$readme_path'") . "<br />";
            }
        }

        return [
            'header' => ['content' => $header, 'type' => $header_type],
            'footer' => ['content' => $footer, 'type' => $footer_type]
        ];
    }
}
