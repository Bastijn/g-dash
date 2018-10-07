<script src="js/dash/main.js?<?php echo $CONFIG['dashversion']; ?>"></script>

<div class="row row-offcanvas row-offcanvas-left">

    <div class="col-sm-3 col-md-2 sidebar-offcanvas" id="sidebar" role="navigation">

        <ul class="nav nav-sidebar">
            <li class="active"><a href="?">Overview</a></li>
            <li><a href="?page=guldend">GuldenD</a></li>
            <li><a href="?page=node">Node</a></li>
            <li><a href="?page=wallet">Wallet</a></li>
            <li><a href="?page=witness">Witness</a></li>
        </ul>
    </div><!--/span-->

    <div class="col-sm-9 col-md-10 main">

        <!--toggle sidebar button-->
        <p class="visible-xs">
            <button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas"><i
                        class="glyphicon glyphicon-chevron-left"></i></button>
        </p>

        <h1 class="page-header">
            Dashboard
            <p class="lead">Server status</p>
        </h1>

        <div id="errordiv"></div>

        <div class="row placeholders">
            <div class="col-xs-6 col-sm-3 placeholder text-center">

                <div id="guldendiv">
                    <h4>Gulden

                        <button id='guldenglyph' class="btn btn-link btn-default pull-right btn-xs"
                                data-toggle="tooltip" data-placement="top" title="" data-original-title="Gulden">
                            <span class="glyphicon glyphicon-question-sign"></span>
                        </button>

                    </h4>
                </div>
                <span class="text-muted"><div id="gulden"><img src="images/loading.gif" border="0" height="64"
                                                               width="64"></div></span>
            </div>
            <div class="col-xs-6 col-sm-3 placeholder text-center">

                <div id="nodediv">
                    <h4>Node

                        <button id='nodeglyph' class="btn btn-link btn-default pull-right btn-xs" data-toggle="tooltip"
                                data-placement="top" title="" data-original-title="Node">
                            <span class="glyphicon glyphicon-question-sign"></span>
                        </button>

                    </h4>
                </div>
                <span class="text-muted"><div id="node"><img src="images/loading.gif" border="0" height="64" width="64"></div></span>
            </div>
            <div class="col-xs-6 col-sm-3 placeholder text-center">

                <div id="witnessdiv">
                    <h4>Witness

                        <button id='witnessglyph' class="btn btn-link btn-default pull-right btn-xs"
                                data-toggle="tooltip" data-placement="top" title="" data-original-title="Witness">
                            <span class="glyphicon glyphicon-question-sign"></span>
                        </button>

                    </h4>
                </div>
                <span class="text-muted"><div id="witness"><img src="images/loading.gif" border="0" height="64"
                                                                width="64"></div></span>
            </div>
            <div class="col-xs-6 col-sm-3 placeholder text-center">

                <div id="serverdiv">
                    <h4>Server

                        <button id='serverglyph' class="btn btn-link btn-default pull-right btn-xs"
                                data-toggle="tooltip" data-placement="top" title="" data-original-title="GuldenD">
                            <span class="glyphicon glyphicon-question-sign"></span>
                        </button>

                    </h4>
                </div>
                <span class="text-muted"><div id="server"><img src="images/loading.gif" border="0" height="64"
                                                               width="64"></div></span>
            </div>
        </div>

        <hr>

        <p class="lead">Last 10 blocks</p>
        <div class="table-responsive">
            <table class="table table-striped" id="tableblocks">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Age</th>
                    <th>Transactions</th>
                    <th>Difficulty</th>
                </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>

    </div><!--/row-->
</div>
