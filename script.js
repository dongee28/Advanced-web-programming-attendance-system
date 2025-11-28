$(function () {
    function recalcRows() {
        $('#attendanceBody tr').each(function () {
            const $row = $(this);
            const totalSlots = 6;

            const presentCount = $row.find('.presence .box.checked').length;
            const absenceCount = totalSlots - presentCount;

            const participationCount = $row.find('.participation .box.checked').length;

            $row.find('.absences').text(absenceCount);
            $row.find('.participation-count').text(participationCount);

            $row.removeClass('row-green row-yellow row-red');
            if (absenceCount < 3) {
                $row.addClass('row-green');
            } else if (absenceCount <= 4) {
                $row.addClass('row-yellow');
            } else {
                $row.addClass('row-red');
            }

            let note;
            if (absenceCount >= 5) {
                note = 'Too many absences';
            } else if (absenceCount >= 3) {
                note = 'Warning';
            } else {
                note = 'Good attendance';
            }

            if (participationCount >= 4) {
                note += ' – Excellent participation';
            } else if (participationCount >= 2) {
                note += ' – Good participation';
            } else {
                note += ' – Low participation';
            }

            $row.find('.message').text(note);
        });
    }

    recalcRows();

    $('#attendanceBody').on('click', '.box', function (evt) {
        evt.stopPropagation();
        const $cellBox = $(this);
        $cellBox.toggleClass('checked');
        $cellBox.text($cellBox.hasClass('checked') ? '✓' : '');
        recalcRows();
    });

    $('#attendanceBody').on('click', 'tr', function (evt) {
        if ($(evt.target).hasClass('box')) {
            return;
        }
        const $row = $(this);
        const ln = $row.data('lastname');
        const fn = $row.data('firstname');
        const abs = $row.find('.absences').text();
        alert('Student: ' + fn + ' ' + ln + '\nAbsences: ' + abs);
    });

    $('#studentForm').on('submit', function (evt) {
        evt.preventDefault();

        $('.error').hide();
        let valid = true;

        const idVal = $('#studentId').val().trim();
        const lastVal = $('#lastName').val().trim();
        const firstVal = $('#firstName').val().trim();
        const mailVal = $('#email').val().trim();

        if (!/^\d+$/.test(idVal)) {
            $('#errorStudentId').show();
            valid = false;
        }
        if (!/^[A-Za-z]+$/.test(lastVal)) {
            $('#errorLastName').show();
            valid = false;
        }
        if (!/^[A-Za-z]+$/.test(firstVal)) {
            $('#errorFirstName').show();
            valid = false;
        }

        const mailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!mailPattern.test(mailVal)) {
            $('#errorEmail').show();
            valid = false;
        }

        if (!valid) {
            return;
        }

        const cells = [1, 2, 3, 4, 5, 6].map(function () {
            return '<td class="presence"><div class="box"></div></td>' +
                   '<td class="participation"><div class="box"></div></td>';
        }).join('');

        const rowHtml =
            '<tr data-lastname="' + lastVal + '" data-firstname="' + firstVal + '">' +
                '<td>' + lastVal + '</td>' +
                '<td>' + firstVal + '</td>' +
                cells +
                '<td class="absences">6</td>' +
                '<td class="participation-count">0</td>' +
                '<td class="message"></td>' +
            '</tr>';

        $('#attendanceBody').append(rowHtml);
        $('#studentForm')[0].reset();
        $('#successMessage').show().delay(2000).fadeOut();
        recalcRows();
    });

    $('#searchInput').on('keyup', function () {
        const query = $(this).val().toLowerCase();
        $('#attendanceBody tr').each(function () {
            const $row = $(this);
            const ln = ($row.data('lastname') || '').toString().toLowerCase();
            const fn = ($row.data('firstname') || '').toString().toLowerCase();
            const match = ln.indexOf(query) !== -1 || fn.indexOf(query) !== -1;
            $row.toggle(match);
        });
    });

    $('#sortByAbsences').on('click', function () {
        const items = $('#attendanceBody tr').get();
        items.sort(function (a, b) {
            const aVal = parseInt($(a).find('.absences').text(), 10);
            const bVal = parseInt($(b).find('.absences').text(), 10);
            return aVal - bVal;
        });
        $('#attendanceBody').empty().append(items);
        $('#sortIndicator').text('Sorted by absences (ascending)');
    });

    $('#sortByParticipation').on('click', function () {
        const items = $('#attendanceBody tr').get();
        items.sort(function (a, b) {
            const aVal = parseInt($(a).find('.participation-count').text(), 10);
            const bVal = parseInt($(b).find('.participation-count').text(), 10);
            return bVal - aVal;
        });
        $('#attendanceBody').empty().append(items);
        $('#sortIndicator').text('Sorted by participation (descending)');
    });

    $('#highlightExcellent').on('click', function () {
        $('#attendanceBody tr').each(function () {
            const $row = $(this);
            const absenceVal = parseInt($row.find('.absences').text(), 10);
            if (absenceVal < 3) {
                $row.addClass('highlight');
                setTimeout(function () {
                    $row.removeClass('highlight');
                }, 2000);
            }
        });
    });

    $('#resetColors').on('click', function () {
        recalcRows();
    });

    $('#showReport').on('click', function () {
        const $rows = $('#attendanceBody tr');
        const totalStudents = $rows.length;
        let presentTotal = 0;
        let participantTotal = 0;

        $rows.each(function () {
            const $row = $(this);
            const hasPresence = $row.find('.presence .box.checked').length > 0;
            const hasPart = $row.find('.participation .box.checked').length > 0;
            if (hasPresence) {
                presentTotal++;
            }
            if (hasPart) {
                participantTotal++;
            }
        });

        $('#totalStudents').text(totalStudents);
        $('#totalPresent').text(presentTotal);
        $('#totalParticipated').text(participantTotal);
        $('#report').show();
    });
});
