<!DOCTYPE html>
<html lang="en">

    @include("layout.headincludes")

    <div class="container" style="margin-top: 10px">
        <h3 style="color: #33A2FF">Random Score Generator</h3>
        <h5 style="color: gray">Instructions</h5>
        <ol style="color: gray">
            <li>Input Score Range (From and To)</li>
            <li>Press <strong>Submit Score</strong></li>
        </ol>
        @yield("content")
    </div>

</html>