/**
 * Created by norman.tong on 01/08/2016.
 */
$(document).ready(function(){

    $("select[name='advertiser']").select2();
    $("select[name='tree']").select2();
    $("select[name='advertiser']").change(function(){
        $("select[name='tree']").val(0);
        $("form[name='select-tree']").submit();
    });
    $("select[name='tree']").change(function(){
        $("form[name='select-tree']").submit();
    });

    /* Control the tabulation key in the tree textarea */
    // $("textarea[name='tree']").keydown(function(e) {
    //     if(e.keyCode === 9) { // tab was pressed
    //         // get caret position/selection
    //         var start = this.selectionStart;
    //         var end = this.selectionEnd;
    //
    //         var $this = $(this);
    //         var value = $this.val();
    //
    //         // set textarea value to: text before caret + tab + text after caret
    //         $this.val(value.substring(0, start)
    //             + "\t"
    //             + value.substring(end));
    //
    //         // put caret at right position again (add one for the tab)
    //         this.selectionStart = this.selectionEnd = start + 1;
    //
    //         // prevent the focus lose
    //         e.preventDefault();
    //     }
    // });
    var myTextArea = document.getElementById('tree-editor');
    var myCodeMirror = CodeMirror.fromTextArea(myTextArea,{
        mode: 'python',
        lineNumbers: true,
        readOnly: false,
        indentUnit: 4,
        indentWithTabs: true
    });
    myCodeMirror.setSize("100%", 700);

})