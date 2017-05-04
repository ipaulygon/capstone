<div id="master-container">
    <div id="form-container">
        <div class="container" id="tabs-container">
            <div class="left-col" id="toolbox-col">
                <ul class="nav-tabs" role="tablist">
                    <li class="active toolbox-tab" data-target="#add-field">Add Question</li>
                    <li class="toolbox-tab" data-target="#field-settings">Question Setting</li>
                    <li class="toolbox-tab" data-target="#form-settings">Change Survey Title</li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade in active" id="add-field">
                        <div style="margin-top:15%">
                            <a href = "#save" class=" new-element btn smoothScroll btn-field btn-field-2 btn-social" data-type="element-single-line-text">Short Text
                                <i class="fa fa-font"></i>
                            </a>
                            <a href = "#save" class="button new-element waves-effect waves-light btn smoothScroll btn-field btn-field-1 btn-social" data-type="element-paragraph-text">Paragraph
                                <i class="fa fa-align-justify"></i>
                            </a>
                            <a href = "#save" class="button new-element waves-effect waves-light btn smoothScroll btn-field btn-field-1 btn-social" data-type="element-multiple-choice">Multiple Choice
                                <i class="glyphicon glyphicon-record"></i>
                            </a>
                            <!-- <a href = "#save" class="button new-element waves-effect waves-light btn smoothScroll btn-field btn-field-1 btn-social" data-type="element-number">Number
                                <i class="fa fa-slack"></i>
                            </a> -->
                            <!-- <a href = "#save" class="button new-element waves-effect waves-light btn smoothScroll btn-field btn-field-1 btn-social" data-type="element-email">Email
                                <i class="fa fa-envelope-o"></i>
                            </a> -->
                            <a href = "#save" class="button new-element btn smoothScroll btn-field btn-social btn-field-1 " data-type="element-checkboxes">Checkboxes
                                <i class="fa fa-check"></i>
                            </a>
                            <a href = "#save" class="button new-element btn smoothScroll btn-field btn-field-1 btn-social" data-type="element-dropdown">Dropdown
                                <i class="fa fa-toggle-down"></i>
                            </a>
                            <a href = "#save" class="button new-element btn smoothScroll btn-field btn-field-1 btn-field-3 btn-social" data-type="element-section-break">Section Break
                            <i class="fa fa-fire"></i></a>   
                        </div>
                    </div>
                <!-- </div> -->
                <div class="tab-pane" id="field-settings">
                <div class="section">
                    <div class="form-group">
                        <label>Field Label</label>
                        <input type="text" class="form-control" id="field-label" value="Untitled" />
                    </div>
                </div>
                <div class="section" id="field-choices" style="display: none;">
                    <div class="form-group">
                        <label>Choices</label>
                    </div>
                </div>
                <div class="section" id="field-options"> 
                    <div class="form-group">
                        <label>Field Options</label>
                    </div>
                    <div class="field-options">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="required">
                                Required
                            </label>
                        </div>
                    </div>
                </div>
                <div class="section" id="field-description"> 
                    <div class="form-group">
                        <label>Field Description</label>
                    </div>
                    <div class="field-description">
                        <textarea id="description"></textarea>
                    </div>
                </div>
                <center>
                <button id="control-remove-field" name="action" class="btn waves-effect btn-danger">Remove
                </button>
                <button style="margin-left: 1%;"id="control-add-field" name="action" class="btn waves-effect btn-primary">Add Field
                </button>
                </center>
            </div>
            <div class="tab-pane" id="form-settings" style="padding-top: 50px; display: none">
                <div class="section">
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" class="bind-control form-control" data-bind="#form-title-label" id="form-title" value="" />
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea class="bind-control form-control" data-bind="#form-description-label" id="form-description"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="right-col" id="form-col">
        <div class="loading">
            <div class="preloader-wrapper big active">
                <div class="spinner-layer spinner-yellow-only">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                        </div><div class="gap-patch">
                        <div class="circle"></div>
                        </div><div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div style="clear: both"></div>
</div>
<div style="clear: both"></div>
<div style="clear: both"></div>
<script src="../assets/js/jquery.smooth-scroll.js" type="text/javascript"></script>
<script type="text/javascript" src="../assets/css/smoothscroll.js"></script>