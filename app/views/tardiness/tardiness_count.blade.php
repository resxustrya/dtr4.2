<html>
    <title>
        Number of days tardiness in the month of july
    </title>
    <head>
        <title>Section Logs</title>
        <link href="{{ asset('public/assets/css/bootstrap.min.css') }}" rel="stylesheet">
        <style>
            table tr th {
                font-size: 1.1em;
            }
        </style>
    </head>
    <body>
        <table class="table table-bordered table-hover">
            <tr class="info">
                <th></th>
                <th>ID Number</th>
                <th>Name</th>
                <th>Position</th>
                <th>Division</th>
                <th>Section</th>
                <th>Employee Status</th>
                <th>Tardiness of this month(Minutes)</th>
            </tr>
            <?php $count = 0; ?>
            @foreach($employee as $row)
            <?php $count++; ?>
            <tr>
                <td>{{ $count }}</td>
                <td>{{ $row->userid }}</td>
                <td>{{ $row->fname.' '.$row->mname.' '.$row->lname }}</td>
                <td>
                    <?php
                        if( $position = Position::find($row->designation_id) ) {
                            $positionName = $position->description;
                        } else {
                            $positionName = "No Position";
                        }
                        echo $positionName;
                    ?>
                </td>
                <td>
                    <?php
                        if( $division = Division::find($row->division_id) ) {
                            $divisionName = $division->description;
                        } else {
                            $divisionName = "No Division";
                        }
                        echo $divisionName;
                    ?>
                </td>
                <td>
                    <?php
                        if( $section = Section::find($row->section_id) ) {
                            $sectionName = $section->description;
                        } else {
                            $sectionName = "No Section";
                        }
                        echo $sectionName;
                    ?>
                </td>
                <td>{{ $row->job_status }}</td>
                <td>
                    <?php
                        echo "Rusel";
                    ?>
                </td>
            </tr>
            @endforeach
        </table>
    </body>
</html>