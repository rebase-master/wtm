<div class="modal fade" id="new-msg-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h2 class="modal-title">New Message</h2>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger text-center hide"><p>Error sending message.</p></div>
                <div class="alert alert-info text-center hide"><p>Message sent successfully.</p></div>
                <form class="form-horizontal">
                    <div class="form-group">
                        <label for="touser" class="col-sm-1">To: </label>
                        <div class="col-sm-11">
                            <input type="text" id="touser" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="sr-only" for="msgbody"></label>
                        <div class="col-sm-12">
                            <textarea id="msgbody" class="form-control"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="sendmsg">Send</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->