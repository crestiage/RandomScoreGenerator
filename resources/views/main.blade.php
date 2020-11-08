@extends("layout.template")

@section("content")

<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css"/>
<script type="text/javascript" src="//cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script> 

<hr>

<div class="row">
    <div class="col-md">
        <form name="main-form" id="main-form">
            <h3>Score Range</h3>
            <div class="form-row">
                <div class="col-md-2">
                    <label for="score-from" class="form-label">From</label>
                    <input type="number" class="form-control" name="score-from" id="score-from" required>
                </div>
                <div class="col-md-2">
                    <label for="score-to" class="form-label">To</label>
                    <input type="number" class="form-control" name="score-to" id="score-to" required>
                </div>
            </div>
            <br />
            <button class="btn btn-primary" type="submit">Submit Score</button> 
        </form>
    </div>
</div>

<hr />

<div class="row">
    <div class="col-md">
        <h3>Previous Submissions</h3>
        <table id="previous-score-submissions" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>Generated Timestamp</th>
                    <th>Score Range - From</th>
                    <th>Score Range - To</th>
                    <th>Score Generated</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>Generated Timestamp</th>
                    <th>Score Range - From</th>
                    <th>Score Range - To</th>
                    <th>Score Generated</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<br />

<div class="row">
    <div class="col-md">
        <h3>Generated Scores per day</h3>
        <table id="generated-scores-per-day" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>No. of Generated Scores</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>Date</th>
                    <th>No. of Generated Scores</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<!-- Hidden Elements -->

<div class="modal" tabindex="-1" role="dialog" id="notification-modal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-title">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p id="modal-text">Modal body text goes here.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="loader"></div>
  <div class="loader-icon"></div>
<body>

<script type="text/javascript">
    $(document).ready(function(){
        
        $("#main-form").on("submit", function(e){
            e.preventDefault();
            var from = $("#score-from").val();
            var to = $("#score-to").val();

            $.ajax({
                type: "POST",
                url: '/api/score/add',
                data: {
                    "from_score_range": from,
                    "to_score_range": to
                }
            })
            .fail(function(msg){
                console.log(JSON.stringify(msg));
                displayModal("Error", msg.responseText.replace(/\"/g, ""), true);
                // alert("Error occurred when submitting the form. Message: " + );
            })
            .done(function(msg) {
                console.log(JSON.stringify(msg));
                displayModal("Score Saved", "The score has been saved. The generated score is: " + msg.score_generated, false);
                scoreTable.ajax.reload();
            });

        });

        var scoreTable = $("#previous-score-submissions").DataTable({
            "ajax": "/api/score/all",
            // Order by created at descending
            "order": [[0, "desc"]],
            "columns": [
                { "data" : "created_at" },
                { "data" : "from_score_range" },
                { "data" : "to_score_range" },
                { "data" : "score_generated" }
            ]
        });

        var scoreCountPerDay = $("#generated-scores-per-day").DataTable({
            "ajax": "/api/score/dailyScoreCount",
            // Order by date_generated at descending
            "order": [[0, "desc"]],
            "columns": [
                { "data" : "date_generated" },
                { "data" : "score_count" }
            ]
        });

        // setInterval( function () {
        //     scoreTable.ajax.reload();
        // }, 30000 );

        $(document).ajaxStart(function() {
            $('.loader').show(); // show the gif image when ajax starts
        }).ajaxStop(function() {
            $('.loader').hide(); // hide the gif image when ajax completes
        });
    });

    function displayModal(title, message, isError){
        if (isError){
            var errorTitle = "<span style='color: red;'>" + title + "</span>";
            $("#modal-title").html(errorTitle);
        }else{
            var successTitle = "<span style='color: green;'>" + title + "</span>";
            $("#modal-title").html(successTitle);
        }
        $("#modal-text").html(message);
        $("#notification-modal").modal("toggle");
    }
</script>

@endsection