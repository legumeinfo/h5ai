const {dom} = require('../util');

const SEL_ROOT = 'body';
const TPL_TOPBAR =
        `<div id="topbar">
        <a href="javascript: location.hostname.includes(\`soybase.org\`) ? window.location = \`https://www.soybase.org/\` : window.location = \`https://www.legumeinfo.org\`">
        <img src="https://www.legumeinfo.org/assets/img/lis-logo-small.png" alt="Legume Information System logo">
        </a>
            <div id="toolbar"></div>
            <div id="flowbar"></div>
            <!--
            <a id="backlink" href="https://larsjung.de/h5ai/" title="powered by h5ai - https://larsjung.de/h5ai/">
                <div>powered</div>
                <div>by h5ai</div>
            </a>
            -->
        </div>`;
const TPL_MAINROW =
        `<div id="mainrow">
            <div id="content"></div>
        </div>`;

const init = () => {
    const $root = dom(SEL_ROOT)
        .attr('id', 'root')
        .clr()
        .app(TPL_TOPBAR)
        .app(TPL_MAINROW);

    return {
        $root,
        $topbar: $root.find('#topbar'),
        $toolbar: $root.find('#toolbar'),
        $flowbar: $root.find('#flowbar'),
        $mainrow: $root.find('#mainrow'),
        $content: $root.find('#content')
    };
};

module.exports = init();
