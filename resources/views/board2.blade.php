<html>
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css"
          integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">

    <link href="{{url('/')}}/css/newKanban.css" rel="stylesheet">


    <script src="https://code.jquery.com/jquery-2.1.3.min.js"
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"
            integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ"
            crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"
            integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm"
            crossorigin="anonymous"></script>
    <script src="{{url('/')}}/js/kanban.js"></script>

    <script src="{{url('/')}}/js/jquery.datetimepicker.full.js"></script>
    <link rel="stylesheet" href="{{url('/')}}/css/jquery.datetimepicker.min.css"/>
    <link rel="shortcut icon" href="{{url('/')}}/favicon.ico" type="image/x-icon">
    <title>Дела</title>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <meta name="user_id" content="{{ $user['id'] }}"/>
    <style>
        .inner-pills {
            padding: 10px;
        }

        .icon-desktop {
            background-image: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz48IURPQ1RZUEUgc3ZnIFBVQkxJQyAiLS8vVzNDLy9EVEQgU1ZHIDEuMS8vRU4iICJodHRwOi8vd3d3LnczLm9yZy9HcmFwaGljcy9TVkcvMS4xL0RURC9zdmcxMS5kdGQiPjxzdmcgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiIHdpZHRoPSIyOC43NXB4IiBoZWlnaHQ9IjI4Ljk5OHB4IiB2aWV3Qm94PSIwIDAgMjguNzUgMjguOTk4IiBlbmFibGUtYmFja2dyb3VuZD0ibmV3IDAgMCAyOC43NSAyOC45OTgiIHhtbDpzcGFjZT0icHJlc2VydmUiPjxnIGlkPSJJY29uc194NUZfQWxsIj48ZyBpZD0iRGFzaGJvYXJkX3g1Rl9uIj48Zz48cGF0aCBmaWxsPSIjQUFCMEI2IiBkPSJNMjIuMTA0LDQuNTc1Yy0xLjk3NC0xLjc0LTQuNTYyLTIuNjMzLTcuMTc3LTIuODAxdjEuMDkyYzAsMC40My0wLjM0NCwwLjc3Mi0wLjc3MSwwLjc3MmMtMC40MjQsMC0wLjc3MS0wLjM0Ny0wLjc3MS0wLjc3MlYxLjc3NGMtMi42MSwwLjE2OC01LjI1NCwxLjE4OC03LjIyMSwyLjkzbDAuNzY4LDAuNzc0YzAuMzAzLDAuMzA0LDAuMzAzLDAuNzkxLDAsMS4wOTNDNi43ODQsNi43MjEsNi41ODcsNi44LDYuMzkxLDYuOGMtMC4xOTYsMC0wLjM5NC0wLjA3NS0wLjU0NC0wLjIyOWwtMC43Ny0wLjc3MmMtMS43MzEsMS45NzktMy4wNDQsNC40NjUtMy4yMSw3LjA5NWgxLjA4NmMwLjQyNSwwLDAuNzY5LDAuMzQ4LDAuNzY5LDAuNzczYzAsMC40MjUtMC4zNDIsMC43NzItMC43NjksMC43NzJIMS44NjljLTAuNjEsMi41NTIsMC40ODYsNS41NywyLjEyMyw3LjA2bDAuNzY4LTAuNzc1YzAuMzAxLTAuMzAxLDAuNzg3LTAuMzAxLDEuMDg3LDBjMC4zLDAuMzA2LDAuMywwLjc5MSwwLDEuMDk3bC0xLjIyOSwxLjIzM2MtMC4wMjEsMC4wMjEtMC4wNDYsMC4wMjctMC4wNjksMC4wNDVjLTAuMDE5LDAuMDIxLTAuMDIyLDAuMDQ4LTAuMDQyLDAuMDYyYy0wLjAzNSwwLjAzNS0wLjEwOS0wLjAyMS0wLjM4My0wLjM1Yy0yLjMxNS0yLjc4NS0yLjk4NC01LjQzLTIuOTg0LTkuMDMyYzAtMy41OTQsMS42ODEtNi42NDYsNC4yMDMtOS4xODhjMi41MjQtMi41NDIsNC4zMzgtMy4zLDcuOTA4LTMuM2wwLjY0Mi0wLjE2OGMzLjA5NSwwLDUuODc2LDAuNTIzLDguMjAzLDIuMzg1YzAuMzU1LDAuMjg3LDAuNzA1LDAuNjAxLDEuMDQzLDAuOTM4YzIuNTIxLDIuNTM4LDQuMjYxLDUuNjY3LDQuMjYxLDkuMjY2YzAsMy42MDMtMS4wNTQsNy4zNDEtMy41NzcsOS44ODJjLTAuMTQ2LDAuMTQ5LTAuMzYzLDAuNDYzLTAuNTYyLDAuNDYzYy0wLjE5MSwwLDAtMC4wNzItMC4xNDYtMC4yMjhjLTAuMDItMC4wMjEtMC4wMjEtMC4wNDktMC4wNDEtMC4wNjVjLTAuMDIxLTAuMDIxLTAuMDQ5LTAuMDIxLTAuMDYzLTAuMDQ1bC0xLjAyMS0xLjAyMWMtMC4yOTktMC4zMDUtMC4yOTktMC43OTEsMC0xLjA5NGMwLjMwMS0wLjMwNywwLjc4NS0wLjMwNywxLjA4NiwwbDAuNzcxLDAuNzcxYzEuNzI5LTEuOTc5LDIuNzI1LTQuMjI5LDIuNzc5LTcuNjhoLTEuMDg2Yy0wLjQyNCwwLTAuNzcxLTAuMzQ2LTAuNzcxLTAuNzcxYzAtMC40MjUsMC4zNDQtMC43NzIsMC43NzEtMC43NzJoMS4wODRjLTAuMTY2LTIuNjM2LTEuMzQ2LTUuNDY1LTMuNDM4LTcuNDk0Ii8+PHBhdGggZmlsbD0ibm9uZSIgc3Ryb2tlPSIjQUFCMEI2IiBzdHJva2UtbWl0ZXJsaW1pdD0iMTAiIGQ9Ik0yNy4yNDYsMTQuMzg0YzAsNy4yMzEtNS43NzEsMTMuMDg4LTEyLjg4MywxMy4wODhjLTcuMTE1LDAtMTIuODg1LTUuODU0LTEyLjg4NS0xMy4wODhDMS40NzgsNy4xNTUsNy4yNDksMS4zLDE0LjM2MywxLjNDMjEuNDc2LDEuMywyNy4yNDYsNy4xNTcsMjcuMjQ2LDE0LjM4NHoiLz48L2c+PGc+PGc+PHBhdGggZmlsbD0iI0FBQjBCNiIgZD0iTTIxLjYxLDUuMjQ1TDE2LjE0LDE0LjQyYy0wLjAxOSwwLjAyMS0wLjAyNiwwLjA1MS0wLjA0MywwLjA3M2MtMC4wNTIsMC4xMDQtMC4xMDYsMC4yMDQtMC4xNzcsMC4yOTVjLTAuMDEsMC4wMTItMC4wMiwwLjAyMS0wLjAyMSwwLjAzM2MtMC4wMSwwLjAwOC0wLjAxOSwwLjAxOC0wLjAyMSwwLjAyMWMtMC4zNzksMC40OC0wLjkzMSwwLjc2My0xLjUxOSwwLjc2M2MtMC4wOTksMC0wLjE5Ni0wLjAxMi0wLjI5NS0wLjAyMWMtMS4wNjctMC4xNjYtMS44MDYtMS4xODgtMS42NC0yLjI3NmMwLjA5LTAuNjA0LDAuNDI0LTAuOTkzLDAuNjg4LTEuMjE4YzAuMDMxLTAuMDI1LDAuMDYyLTAuMDU5LDAuMDkxLTAuMDg3TDIxLjYxLDUuMjQ1IE0yNS4zODYsMC45MDRoMC4wMDRIMjUuMzg2eiBNMjUuMzg2LDAuOTA0bC0xMi45NCwxMC4zOThsMC4wMDgsMC4wMWMtMC41MzMsMC40NTQtMC45MiwxLjA4Ny0xLjAzNCwxLjg0MmMtMC4yNSwxLjY0NywwLjg2NiwzLjE5MSwyLjQ4OCwzLjQ0NWMwLjE0OCwwLjAyMSwwLjMwMiwwLjAzNSwwLjQ1LDAuMDM1YzAuOTIzLDAsMS43NjctMC40NDcsMi4zMTctMS4xNTZsMC4wMTIsMC4wMTRsMC4wNi0wLjEwNGMwLjEwNC0wLjE0MywwLjE4OC0wLjI4OCwwLjI3LTAuNDQ0TDI1LjM4NiwwLjkwNEwyNS4zODYsMC45MDR6Ii8+PC9nPjxnPjxwb2x5Z29uIGZpbGw9IiNBQUIwQjYiIHBvaW50cz0iMjMuNjY4LDIuODM5IDIyLjc4MSwzLjI1OCAyMy40MDYsMy45MjMgIi8+PC9nPjwvZz48cGF0aCBmaWxsPSJub25lIiBzdHJva2U9IiNBQUIwQjYiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCIgc3Ryb2tlLW1pdGVybGltaXQ9IjEwIiBkPSJNMTguMDA5LDIxLjU1OGMwLDAuNzcxLTAuNjA5LDEuMzk2LTEuMzcxLDEuMzk2aC01LjE4NmMtMC43NTksMC0xLjM3Mi0wLjYyNS0xLjM3Mi0xLjM5NnYtMC4yMjljMC0wLjc3MSwwLjYxMy0xLjM5NiwxLjM3Mi0xLjM5Nmg1LjE4NmMwLjc2MiwwLDEuMzcxLDAuNjIzLDEuMzcxLDEuMzk2VjIxLjU1OHoiLz48L2c+PC9nPjwvc3ZnPg==);
            height: 30px;
            margin: 5px 17.5px;
            width: 30px;
        }

        .icon-lead {
            background-image: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz48IURPQ1RZUEUgc3ZnIFBVQkxJQyAiLS8vVzNDLy9EVEQgU1ZHIDEuMS8vRU4iICJodHRwOi8vd3d3LnczLm9yZy9HcmFwaGljcy9TVkcvMS4xL0RURC9zdmcxMS5kdGQiPjxzdmcgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiIHdpZHRoPSIyOS4wMzdweCIgaGVpZ2h0PSIzNS45cHgiIHZpZXdCb3g9Ii0wLjc2NSAtMSAyOS4wMzcgMzUuOSIgZW5hYmxlLWJhY2tncm91bmQ9Im5ldyAtMC43NjUgLTEgMjkuMDM3IDM1LjkiIHhtbDpzcGFjZT0icHJlc2VydmUiPjxnIGlkPSJJY29uc194NUZfQWxsIj48ZyBpZD0iTGVhZHNfeDVGX24iPjxwYXRoIGZpbGw9Im5vbmUiIHN0cm9rZT0iI0FBQjBCNiIgc3Ryb2tlLW1pdGVybGltaXQ9IjEwIiBkPSJNMjAuODE4LDkuNDM5YzMuNTkxLDIuMzEyLDUuOTcsNi4zNDcsNS45NywxMC45MzhjMCw3LjE4LTUuODE3LDEzLTEzLjAwMiwxM2MtNy4xODMsMC0xMy01LjgyLTEzLTEzYzAtMS40NTUsMC4yMzctMi44NTUsMC42OC00LjE2MmwwLjA3Mi0wLjQyMiIvPjxnPjxnPjxnPjxnPjxnPjxnPjxnPjxnPjxnPjxkZWZzPjxwYXRoIGlkPSJTVkdJRF8xXyIgZD0iTTI2Ljc4NiwyMC4zNzRjMCw3LjE4MS01LjgxNSwxMy0xMy4wMDIsMTNjLTcuMTgxLDAtMTMtNS44MTktMTMtMTNjMC03LjE4MS04LjQ0NS0yMy4yNDMsMTMtMjMuMjQzQzI0Ljg0Ni0yLjg2NywyNi43ODYsMTMuMTk1LDI2Ljc4NiwyMC4zNzR6Ii8+PC9kZWZzPjxjbGlwUGF0aCBpZD0iU1ZHSURfMl8iPjx1c2UgeGxpbms6aHJlZj0iI1NWR0lEXzFfIiAgb3ZlcmZsb3c9InZpc2libGUiLz48L2NsaXBQYXRoPjxnIGNsaXAtcGF0aD0idXJsKCNTVkdJRF8yXykiPjxwYXRoIGZpbGw9Im5vbmUiIHN0cm9rZT0iI0FBQjBCNiIgc3Ryb2tlLW1pdGVybGltaXQ9IjEwIiBkPSJNOS4yODksNDAuMjU3di00LjYzMWMtMy4yNjctMC4xNDYtNi40MzUtMS4wMjEtOC4yODUtMi4wOTZsMS40NjItNS43MDFjMi4wNDcsMS4xMjEsNC45MjMsMi4xNDYsOC4wOSwyLjE0NmMyLjc3OCwwLDQuNjgyLTEuMDY0LDQuNjgyLTMuMDIxYzAtMS44NTQtMS41NjItMy4wMjEtNS4xNjctNC4yMzZjLTUuMjE3LTEuNzU4LTguNzczLTQuMTkyLTguNzczLTguOTIxYzAtNC4yODksMy4wMjEtNy42NTEsOC4yMzYtOC42NzZWMC40OWg0Ljc3NnY0LjI5YzMuMjY5LDAuMTQ2LDUuNDYxLDAuODI4LDcuMDY1LDEuNjA3bC0xLjQxMyw1LjUwOGMtMS4yNzEtMC41MzYtMy41MDktMS42NTctNy4wMjMtMS42NTdjLTMuMTY4LDAtNC4xODksMS4zNjQtNC4xODksMi43MjljMCwxLjYwNiwxLjcwNiwyLjYzMiw1Ljg1NCw0LjE4OWM1LjgwNiwyLjA0OCw4LjE0NCw0LjcyOSw4LjE0NCw5LjExNWMwLDQuMzM4LTMuMDY3LDguMDM5LTguNjc4LDkuMDE0djQuOTc1TDkuMjg5LDQwLjI1N0w5LjI4OSw0MC4yNTd6Ii8+PC9nPjwvZz48L2c+PC9nPjwvZz48L2c+PC9nPjwvZz48L2c+PC9nPjwvZz48L2c+PC9zdmc+);
            height: 37px;
            margin: 5px 17.5px;
            width: 30px;
        }

        .icon-task2 {
            background-image: url("{{url('/')}}/images/logo_active.svg");
            height: 35px;
            margin: 5px 17.5px;
            width: 31px;
        }

        .icon-task {
            background-image: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz48IURPQ1RZUEUgc3ZnIFBVQkxJQyAiLS8vVzNDLy9EVEQgU1ZHIDEuMS8vRU4iICJodHRwOi8vd3d3LnczLm9yZy9HcmFwaGljcy9TVkcvMS4xL0RURC9zdmcxMS5kdGQiPjxzdmcgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiIHdpZHRoPSIzMS4wNjJweCIgaGVpZ2h0PSIzMC40ODJweCIgdmlld0JveD0iMCAwIDMxLjA2MiAzMC40ODIiIGVuYWJsZS1iYWNrZ3JvdW5kPSJuZXcgMCAwIDMxLjA2MiAzMC40ODIiIHhtbDpzcGFjZT0icHJlc2VydmUiPjxnIGlkPSJJY29uc194NUZfQWxsIj48ZyBpZD0iVGFza194NUZfbiI+PGNpcmNsZSBmaWxsPSJub25lIiBzdHJva2U9IiNBQUIwQjYiIHN0cm9rZS1taXRlcmxpbWl0PSIxMCIgY3g9IjE0LjU5MyIgY3k9IjE1Ljk0OCIgcj0iMTMiLz48cG9seWxpbmUgZmlsbD0ibm9uZSIgc3Ryb2tlPSIjQUFCMEI2IiBzdHJva2Utd2lkdGg9IjIiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCIgc3Ryb2tlLW1pdGVybGltaXQ9IjEwIiBwb2ludHM9IjI5LjA1LDIuMDUyIDE0LjE3NywyMS4wNSA5LjA0NywxNS43NzIgIi8+PC9nPjwvZz48L3N2Zz4=);
            height: 30px;
            margin: 5px 17.5px;
            width: 30px;
        }

        .icon-lists {
            background-image: url(data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI1NSIgaGVpZ2h0PSI2NCIgdmlld0JveD0iMCAwIDU1IDY0Ij4KICA8ZGVmcz4KICAgIDxzdHlsZT4KICAgICAgLmNscy0xIHsKICAgICAgICBmaWxsOiAjYWFiMGI2OwogICAgICAgIGZpbGwtcnVsZTogZXZlbm9kZDsKICAgICAgfQogICAgPC9zdHlsZT4KICA8L2RlZnM+CiAgPHBhdGggaWQ9Ikxpc3QiIGNsYXNzPSJjbHMtMSIgZD0iTTE5OCwxNDM1LjYyVjE0MzZoLTAuMjg3YTI3LjUsMjcuNSwwLDAsMS00Mi40MjcsMEgxNTV2LTAuMzhhMjcuMzM5LDI3LjMzOSwwLDAsMSwwLTM0LjE4VjEzODZoNDN2MTUuMzhBMjcuNDI1LDI3LjQyNSwwLDAsMSwxOTgsMTQzNS42MlptLTQzLTMwLjc3YTI1LjMyNCwyNS4zMjQsMCwwLDAsMCwyNy4zMnYtMjcuMzJaTTE5NiwxMzg4SDE1N3Y0NmwxLDJoLTAuMDE0YTI1LjQyNSwyNS40MjUsMCwwLDAsMzcuMDMsMEgxOTVsMS0ydi00NlptMiw0NC4xN2EyNS4zNTksMjUuMzU5LDAsMCwwLDAtMjcuMzR2MjcuMzRaTTE3MywxNDMxaDE4djJIMTczdi0yWm0wLTE1aDE4djJIMTczdi0yWm0wLTE1aDE4djJIMTczdi0yWm0wLTZoOHYyaC04di0yWm0tMTEsMzBoOHY4aC04di04Wm0yLDZoNHYtNGgtNHY0Wm0tMi0yMWg4djhoLTh2LThabTIsNmg0di00aC00djRabS0yLTIxaDh2OGgtOHYtOFptMiw2aDR2LTRoLTR2NFptMTcsMTFoLTh2LTJoOHYyWm0wLDE1aC04di0yaDh2MlptLTIzLTQ1aDM3djJIMTU4di0yWiIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoLTE0OSAtMTM4MikiLz4KPC9zdmc+Cg==);
            height: 34px;
            margin: 5px 17.5px;
            width: 30px;
            background-size: 100%;
        }

        .icon-mail {
            background-image: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz48IURPQ1RZUEUgc3ZnIFBVQkxJQyAiLS8vVzNDLy9EVEQgU1ZHIDEuMS8vRU4iICJodHRwOi8vd3d3LnczLm9yZy9HcmFwaGljcy9TVkcvMS4xL0RURC9zdmcxMS5kdGQiPjxzdmcgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiIHdpZHRoPSIzMi44MDNweCIgaGVpZ2h0PSIyOS4wMjVweCIgdmlld0JveD0iMCAwIDMyLjgwMyAyOS4wMjUiIGVuYWJsZS1iYWNrZ3JvdW5kPSJuZXcgMCAwIDMyLjgwMyAyOS4wMjUiIHhtbDpzcGFjZT0icHJlc2VydmUiPjxnIGlkPSJJY29uc194NUZfQWxsIj48ZyBpZD0iTWFpbF94NUZfbiI+PHBhdGggZmlsbD0ibm9uZSIgc3Ryb2tlPSIjQUFCMEI2IiBzdHJva2UtbWl0ZXJsaW1pdD0iMTAiIGQ9Ik0yNC43NTQsMjIuODc3Ii8+PHBhdGggZmlsbD0ibm9uZSIgc3Ryb2tlPSIjQUFCMEI2IiBzdHJva2UtbWl0ZXJsaW1pdD0iMTAiIGQ9Ik0yNC40MDcsNS43MDdjLTIuMzc3LTIuNTkyLTUuNzg2LTQuMjE3LTkuNTk3LTQuMjE3Yy03LjE3OSwwLTEzLDUuODIxLTEzLDEzYzAsNy4xODQsNS44MiwxMywxMywxM2MzLjk5NSwwLDcuNTYxLTEuNzg5LDkuOTQ3LTQuNjA4bDEuNTE0LTEuODU0Ii8+PGc+PGc+PGc+PGc+PGc+PGc+PGc+PGc+PGRlZnM+PHBhdGggaWQ9IlNWR0lEXzFfIiBkPSJNMzUuODMyLDE0LjQ4N2MwLDcuMTg0LTEzLjgzLDEzLTIxLjAyNCwxM2MtNy4xNzgsMC0xMi45OTgtNS44MTYtMTIuOTk4LTEzYzAtNy4xNzgsNS44MTktMTMsMTIuOTk4LTEzQzIyLjAwMiwxLjQ4OCwzNS44MzItMS4xNjcsMzUuODMyLDE0LjQ4N3oiLz48L2RlZnM+PGNsaXBQYXRoIGlkPSJTVkdJRF8yXyI+PHVzZSB4bGluazpocmVmPSIjU1ZHSURfMV8iICBvdmVyZmxvdz0idmlzaWJsZSIvPjwvY2xpcFBhdGg+PGcgY2xpcC1wYXRoPSJ1cmwoI1NWR0lEXzJfKSI+PHBhdGggZmlsbD0ibm9uZSIgc3Ryb2tlPSIjQUFCMEI2IiBzdHJva2UtbWl0ZXJsaW1pdD0iMTAiIGQ9Ik0yNi41NjcsMjAuNDQzYy0wLjU1NCwxLjM3Ny0yLjExMSwyLjQ5NC0zLjQ4NiwyLjQ5NEgzLjEyNmMtMS4zNzUsMC0yLjA0Ni0xLjExNy0xLjQ5NS0yLjQ5NEw2LjI4NCw4LjgxNmMwLjU1My0xLjM3NSwxLjM5Ni0zLjAwOCwzLjM3LTMuMDA4aDE5Ljc3OGMxLjk3OSwwLDIuMzM0LDEuNjMyLDEuNzgyLDMuMDA4TDI2LjU2NywyMC40NDN6Ii8+PHBvbHlsaW5lIGZpbGw9Im5vbmUiIHN0cm9rZT0iI0FBQjBCNiIgc3Ryb2tlLW1pdGVybGltaXQ9IjEwIiBwb2ludHM9IjI3LjU2Myw5LjY0NiAxNS43NTMsMTYuMjkxIDkuMjc1LDkuNjQ2ICIvPjxsaW5lIGZpbGw9Im5vbmUiIHN0cm9rZT0iI0FBQjBCNiIgc3Ryb2tlLW1pdGVybGltaXQ9IjEwIiB4MT0iNS4wNDQiIHkxPSIyMC4zMDEiIHgyPSIxMC4zMTIiIHkyPSIxNi4zNDgiLz48bGluZSBmaWxsPSJub25lIiBzdHJva2U9IiNBQUIwQjYiIHN0cm9rZS1taXRlcmxpbWl0PSIxMCIgeDE9IjIzLjUwMiIgeTE9IjIwLjMwMSIgeDI9IjIwLjg2NyIgeTI9IjE2LjM0OCIvPjwvZz48L2c+PC9nPjwvZz48L2c+PC9nPjwvZz48L2c+PC9nPjwvZz48L2c+PC9zdmc+);
            height: 30px;
            margin: 5px 17.5px;
            width: 33px;
        }

        .icon-analytic {
            background-image: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz48IURPQ1RZUEUgc3ZnIFBVQkxJQyAiLS8vVzNDLy9EVEQgU1ZHIDEuMS8vRU4iICJodHRwOi8vd3d3LnczLm9yZy9HcmFwaGljcy9TVkcvMS4xL0RURC9zdmcxMS5kdGQiPjxzdmcgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiIHdpZHRoPSIzOC45NDVweCIgaGVpZ2h0PSIyOXB4IiB2aWV3Qm94PSIwIDAgMzguOTQ1IDI5IiBlbmFibGUtYmFja2dyb3VuZD0ibmV3IDAgMCAzOC45NDUgMjkiIHhtbDpzcGFjZT0icHJlc2VydmUiPjxnIGlkPSJJY29uc194NUZfQWxsIj48ZyBpZD0iQW5hbHl0aWNzX3g1Rl9uIj48cGF0aCBmaWxsPSJub25lIiBzdHJva2U9IiNBQUIwQjYiIHN0cm9rZS1taXRlcmxpbWl0PSIxMCIgZD0iTTE5LjYyMiwxLjc1OGM3LjE3NywwLDEyLjk5NSw1LjgyLDEyLjk5NSwxM2MwLDcuMTgyLTUuODE2LDEzLTEyLjk5NSwxM2MtNy4xOCwwLTEzLTUuODE4LTEzLTEzQzYuNjIyLDcuNTc4LDEyLjQ0MiwxLjc1OCwxOS42MjIsMS43NTh6Ii8+PHBhdGggZmlsbD0iI0FBQjBCNiIgZD0iTTMuNzY2LDI0LjI3OWMxLjU0LDAsMi43OTEtMS4yNTQsMi43OTEtMi43OTNjMC0wLjQ0NS0wLjExNC0wLjg1Ny0wLjMwMi0xLjIzNGw2LjM5Ni01LjU0MmMwLjQ0OCwwLjMxMiwwLjk5NiwwLjQ5OSwxLjU4MiwwLjQ5OWMwLjkyMiwwLDEuNzMzLTAuNDU2LDIuMjQyLTEuMTQ2bDUuNDczLDIuMTg4Yy0wLjAxNywwLjExNC0wLjAzMywwLjIyOS0wLjAzMywwLjM1M2MwLDEuNTQxLDEuMjU4LDIuNzk2LDIuNzg4LDIuNzk2YzEuNTM5LDAsMi43OTEtMS4yNTUsMi43OTEtMi43OTZjMC0wLjYwNC0wLjE5NC0xLjE2My0wLjUyNC0xLjYyNGw3LjAyMS04LjQyN2MwLjM2MSwwLjE3MywwLjc2OCwwLjI3NywxLjE4OCwwLjI3N2MxLjU0MSwwLDIuNzkzLTEuMjU2LDIuNzkzLTIuNzk1YzAtMS41MzgtMS4yNTItMi43OTItMi43OTMtMi43OTJjLTEuNTQsMC0yLjc5MSwxLjI1NC0yLjc5MSwyLjc5MmMwLDAuNjA0LDAuMTk5LDEuMTY4LDAuNTI0LDEuNjIybC03LjAyMSw4LjQzMWMtMC4zNTktMC4xNzQtMC43NjQtMC4yNzctMS4xODgtMC4yNzdjLTAuOTIsMC0xLjcyOCwwLjQ1Ny0yLjIzMywxLjE0NmwtNS40NzYtMi4xOTNjMC4wMi0wLjExMiwwLjAzNS0wLjIyOSwwLjAzNS0wLjM1NGMwLTEuNTM2LTEuMjU0LTIuNzkxLTIuNzkzLTIuNzkxYy0xLjU0LDAtMi43OTIsMS4yNTUtMi43OTIsMi43OTFjMCwwLjQ1LDAuMTEzLDAuODYsMC4zLDEuMjM3bC02LjM5Niw1LjU0NmMtMC40NTEtMC4zMTItMC45OTUtMC40OTgtMS41ODItMC40OThjLTEuNTQyLDAtMi43OTMsMS4yNTQtMi43OTMsMi43OTVDMC45NzMsMjMuMDI1LDIuMjI0LDI0LjI3OSwzLjc2NiwyNC4yNzl6Ii8+PC9nPjwvZz48L3N2Zz4=);
            height: 30px;
            margin: 5px 13.5px;
            width: 40px;
        }

        .icon-setting {
            background-image: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz48IURPQ1RZUEUgc3ZnIFBVQkxJQyAiLS8vVzNDLy9EVEQgU1ZHIDEuMS8vRU4iICJodHRwOi8vd3d3LnczLm9yZy9HcmFwaGljcy9TVkcvMS4xL0RURC9zdmcxMS5kdGQiPjxzdmcgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiIHdpZHRoPSIzMy4xNjRweCIgaGVpZ2h0PSIzNC40MzhweCIgdmlld0JveD0iMCAwIDMzLjE2NCAzNC40MzgiIGVuYWJsZS1iYWNrZ3JvdW5kPSJuZXcgMCAwIDMzLjE2NCAzNC40MzgiIHhtbDpzcGFjZT0icHJlc2VydmUiPjxnIGlkPSJJY29uc194NUZfQWxsIj48ZyBpZD0iU2V0dGluZ3NfeDVGX24iPjxwYXRoIGZpbGw9Im5vbmUiIHN0cm9rZT0iI0FBQjBCNiIgc3Ryb2tlLW1pdGVybGltaXQ9IjEwIiBkPSJNMjcuMTQ4LDE2Ljk5NGMwLjIxNSwwLjkzOSwwLjMyOCwxLjkxNiwwLjMyOCwyLjkxOGMwLDcuMTg0LTUuODE4LDEzLTEzLjAwMSwxM2MtNy4xODEsMC0xMy01LjgxOC0xMy0xM2MwLTcuMTg4LDUuODE5LTEzLjAwOCwxMy0xMy4wMDhjMC40MDgsMCwwLjgxMiwwLjAyMSwxLjIxNSwwLjA2Ii8+PGc+PGc+PGc+PGc+PGc+PGc+PGc+PGc+PGRlZnM+PHBhdGggaWQ9IlNWR0lEXzFfIiBkPSJNMjcuNDc3LDE5LjkxNGMwLDcuMTgyLTUuODE4LDEzLTEzLjAwMSwxM2MtNy4xODEsMC0xMy01LjgyLTEzLTEzYzAtNy4xODksNS44MTktMTMuMDA5LDEzLTEzLjAwOWMxLjE5OCwwLDEuNTM2LTQuNDgzLDQuMDAxLTUuNjNjNC4xMDUtMS45MDYsMTAuNzI5LTAuNzMyLDEyLjE0MywwLjQ1OWMxLjUwMywxLjI3MSw0LjAwMyw3LjM0NSwyLjc1MywxMS4zNDdDMzIuMjcsMTYuNjAyLDI3LjQ3NywxOC4wNDUsMjcuNDc3LDE5LjkxNHoiLz48L2RlZnM+PGNsaXBQYXRoIGlkPSJTVkdJRF8yXyI+PHVzZSB4bGluazpocmVmPSIjU1ZHSURfMV8iICBvdmVyZmxvdz0idmlzaWJsZSIvPjwvY2xpcFBhdGg+PHBhdGggY2xpcC1wYXRoPSJ1cmwoI1NWR0lEXzJfKSIgZmlsbD0ibm9uZSIgc3Ryb2tlPSIjQUFCMEI2IiBzdHJva2UtbWl0ZXJsaW1pdD0iMTAiIGQ9Ik0tNi40NDUsMzcuNzg1bDAuODQ5LDIuNzNjMC4wODQsMC4yNywwLjMwOCwwLjQ3NywwLjU4NCwwLjUzOWwyLjc4NywwLjYzMWMwLjI3NiwwLjA2MiwwLjU2Ny0wLjAyNywwLjc2LTAuMjM0bDEuOTQtMi4xYzAuMTg4LTAuMjA5LDAuMjYxLTAuNTA0LDAuMTc1LTAuNzczbC0wLjg0Ny0yLjcyOWMtMC4wODYtMC4yNzEtMC4zMTEtMC40NzctMC41ODQtMC41MzlsLTIuNzg5LTAuNjI5Yy0wLjI3NC0wLjA2Mi0wLjU2NSwwLjAyNS0wLjc1OSwwLjIzMmwtMS45NCwyLjEwMkMtNi40NjIsMzcuMjIzLTYuNTI5LDM3LjUxOC02LjQ0NSwzNy43ODV6IE0tOC4zMDEsMzMuMTg4YzEuNTI0LTEuNjQ1LDMuNjI5LTIuNDM5LDUuNzE1LTIuMzU0YzEuMjI5LDAuMDUzLDIuNDE2LTAuNDQ1LDMuMjUyLTEuMzU0bDEzLjE0Ni0xNC4yMjhjMS4xNjQtMS4yNTksMS43NDMtMi45NDQsMS42MDgtNC42NTZjLTAuMjAyLTIuNjUyLDAuODM3LTUuMzczLDMuMDIxLTcuMThjMS45LTEuNTY2LDQuMjc1LTIuMTU3LDYuNTI1LTEuODQ2YzAuODU0LDAuMTIsMS4xNDYsMS4yMTYsMC40NzEsMS43NTNsLTMuNDcxLDIuNzUzYy0wLjU4OCwwLjQ2OS0wLjgxMiwxLjI2My0wLjU0NywxLjk2N2MwLjAxNCwwLjAyOCwwLjAyMSwwLjA2MiwwLjAzMSwwLjA5MmMwLjYsMS41NDcsMS42NDYsMi44NzQsMy4wMjEsMy44MDNsMC4wNTUsMC4wMzNjMC42MjcsMC40MjYsMS40NTcsMC4zOTUsMi4wNTUtMC4wNzZsMy40NzItMi43NTRjMC42NzktMC41MzUsMS42NzktMC4wMDUsMS41OTksMC44NTRjLTAuMjA3LDIuMjY1LTEuMzIyLDQuNDQxLTMuMjgxLDUuOTM4Yy0xLjUxNCwxLjE1Ny0zLjMwNSwxLjcxNy01LjA4NiwxLjcxOWMtMS43MjksMC4wMDEtMy4zODMsMC43MDEtNC41NTksMS45N0w1LjQ5LDMzLjk1MWMtMC44MzYsMC44OTYtMS4yMzgsMi4xMjctMS4wOTMsMy4zNTJjMC4yODMsMi4zMjYtMC41MzgsNC43Ny0yLjUwOCw2LjQ3M2MtMi41NjMsMi4yMTctNi40MTIsMi4zNjMtOS4xNCwwLjM1NUMtMTAuODM5LDQxLjQ3My0xMS4yNSwzNi4zNzctOC4zMDEsMzMuMTg4eiIvPjwvZz48L2c+PC9nPjwvZz48L2c+PC9nPjwvZz48L2c+PC9nPjwvZz48L3N2Zz4=);
            height: 33px;
            margin: 5px 17.5px;
            width: 32px;
        }

        .li-tasks {
            margin: 16px 0px;
            font-size: 11px;
            text-align: center;
            line-height: 11px;
            color: #9da8ae;

        }

        .li-tasks > a {
            /* text-decoration: none; */
            color: #9da8ae;
        }

        .li-tasks > a:hover {
            color: #fff;
        }

        .inner-icon {
            width: 20px;
            display: inline-block;
            height: 20px;
        }

        .inner-icon-edit {
            background: url({{url('/')}}/images/new/edit-20px.svg);
            background-size: 20px;
        }

        .inner-icon-edit:hover {
            background: url({{url('/')}}/images/new/edit-hover-20px.svg);
            background-size: 20px;
        }

        .inner-icon-done {
            background: url({{url('/')}}/images/new/done-20px.svg);
            background-size: 20px;
        }

        .inner-icon-done:hover {
            background: url({{url('/')}}/images/new/done-hover-20px.svg);
            background-size: 20px;
        }

        .inner-icon-end {
            background: url({{url('/')}}/images/new/call-20px.svg);
            background-size: 20px;
        }

        .inner-icon-end:hover {
            background: url({{url('/')}}/images/new/call-hover-20px.svg);
            background-size: 20px;
        }

        .inner-icon-more {
            background: url({{url('/')}}/images/outline-more_horiz-24px_hover.svg);
            background-size: 20px;
        }

        .inner-icon-more:hover {
            background: url({{url('/')}}/images/outline-more_horiz-24px.svg);
            background-size: 20px;
        }

    </style>
</head>
<div id="overlay">
    <div class="overlay-block">
        <span class="overlay-text">У вас нет созданных дел!<br>Создайте дело прямо сейчас?</span><br>
        <a class="btn btn-primary btn-lg" style="color: #f5f5f5;margin: 20px; "
           data-toggle="modal" data-target="#createTask" onclick="addTaskModal()">Добавить дело</a>
    </div>
</div>
<body>
<div class="left-menu" style="
    float: left;
    background: #1b3446;
    width: 65px;
    height: 100%;
">
    <div class="delitemer-left" style="
    height: 65px;
    border-bottom: 1px solid #eee;
">
        <img src="{{url('/')}}/images/avatar.png">
    </div>
    <ul style="
    color: #fff;
    list-style-type: none;
    padding: 0;
">
        <li class="li-tasks">
            <a href="https://novyetechnologii.amocrm.ru/dashboard/">
                <div class="icon-desktop"></div>
                Рабочий стол</a>
        </li>
        <li class="li-tasks">
            <a href="https://novyetechnologii.amocrm.ru/leads/">
                <div class="icon-lead"></div>
                Сделки</a>
        </li>
        <li class="li-tasks" style="color: #FFF;">
            <a href="/">
                <div class="icon-task2"></div>
                Дела</a>
        </li>
        <li class="li-tasks">
            <a href="https://novyetechnologii.amocrm.ru/todo/line/">
                <div class="icon-task"></div>
                Задачи</a>
        </li>
        <li class="li-tasks">
            <a href="https://novyetechnologii.amocrm.ru/contacts/list/">
                <div class="icon-lists"></div>
                Списки</a>
        </li>
        <li class="li-tasks">
            <a href="https://novyetechnologii.amocrm.ru/mail/inbox/">
                <div class="icon-mail"></div>
                Почта</a>
        </li>
        <li class="li-tasks">
            <a href="https://novyetechnologii.amocrm.ru/stats/">
                <div class="icon-analytic"></div>
                Аналитика</a>
        </li>
        <li class="li-tasks">
            <a href="https://novyetechnologii.amocrm.ru/settings/widgets/">
                <div class="icon-setting"></div>
                Настройки</a>
        </li>
    </ul>
</div>
<nav class="navbar navbar-expand-lg navbar-light" style="
    background-color: #fff;
    height: 65px;
    padding: 0px;
    border-bottom: 1px solid #e8eaeb;
    ">
    <span class="navbar-brand mb-0 h1" style="
    border-right: 1px solid #e8eaeb;
    height: 100%;
    margin: 0;
    padding: 16px 28px;
">ДЕЛА</span>
    <div class="navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav" style="
    height: 100%;
    padding:  13px 5px;
    border-right: 1px solid #e8eaeb;
">
            <div class="dropdown" style="
    min-width: 50px;
    padding: 5px 23px;
">
                <a class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown">
                    <img src="{{url('/')}}/images/outline-center_focus_strong-24px.svg" style="
    width: 28px;
">
                </a>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <li class="dropdown-item" onclick="changeMode('all')">Все <span
                                class="sr-only">(current)</span></li>
                    <li class="dropdown-item" onclick="changeMode('expired')">Просроченные</li>
                    <li class="dropdown-item" onclick="changeMode('today')">Сегодня</li>
                    <li class="dropdown-divider"></li>
                    <li class="dropdown-item" onclick="setIntervalMenuModal()">Выбрать дату</li>
                    <li class="dropdown-submenu"><a class="dropdown-item dropdown-toggle" href="#">Выбрать приоритет</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" onclick="changeMode('type|1')">Высокий</a></li>
                            <li><a class="dropdown-item" onclick="changeMode('type|3')">Средний</a></li>
                            <li><a class="dropdown-item" onclick="changeMode('type|2')">Низкий</a></li>
                        </ul>
                    </li>
                    <li class="dropdown-divider"></li>
                    <li class="dropdown-item" onclick="changeMode('end')">Завершенные</li>
                    <li class="dropdown-divider"></li>
                    <li class="dropdown-item" onclick="showNotificationModal()">Уведомления</li>
                </ul>
            </div>
        </div>
        <form class="form-inline my-2 my-lg-0" style="
    padding: 0 10px;
    width: 100%;
    border-right: 1px solid #e8eaeb;
    height: 100%;

    ">
            <div class="input-group mb-3" style="
    width: -webkit-fill-available;
    margin: inherit !important;
">
                <input type="text" class="form-control" id="searchText" placeholder="Поиск" aria-label="Поиск"
                       aria-describedby="basic-addon2">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="button"
                            style="color: #fff; -webkit-border-radius: 0px;-moz-border-radius: 0px;border-radius: 0px;"
                            onclick="searchit();">Поиск
                    </button>
                </div>
            </div>
        </form>

        <a class="btn btn-primary"
           style="color: #f5f5f5;margin: 10px;text-transform: uppercase;font-family: 'Arial';font-weight: 600;font-size: 15px;border-radius: 0px;"
           data-toggle="modal" data-target="#createTask" onclick="addTaskModal()">+ Добавить задачу</a>
    </div>

</nav>
<div class="container-fluid">
    <div class="row">
        @foreach($board as $pipeline)
            <div class="col-sm" style="padding: 0">
                <div class="pipeline ">
                    <div class="pipeline-header font-weight-bold">
                        {{$pipeline['name']}}<br>
                        <span style="
    color: #666;
    font-weight: 400;
    line-height: 20px;
"><span id="pipeline{{$pipeline['id']}}_count_task">0</span> задач</span>
                    </div>
                    <div class="pipeline-body" id="pipeline{{$pipeline['id']}}">

                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
</body>


<div class="modal fade" id="createTask" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="
    border-radius: 0px;
">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Создание задачи</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form role="form" action="{{url('/')}}/tasks/add" method="post">

                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="user_id" id="addTask_user_id" value="{{$user['id']}}">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Наименование сделки</label>
                        <input type="text" class="form-control" id="name_lead"
                               placeholder="ХХ-0000-00">
                        <div class="progress" id="loaderLeads" style="height: 5px; margin-top: 5px; display: none">
                            <div class="progress-bar" id="innerLoaderLeads" role="progressbar" style="width: 0%;"
                                 aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <select style="margin-top: 5px; display: none" class="form-control" name="lead_id"
                                id="lead_select">
                            <option disabled="disabled">----</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Воронка</label>
                        <select name="pipeline_id" id="addTask_pipeline_id" class="form-control">
                            <option value="1">Новая заявка</option>
                            <option value="2">Подбор поставщика</option>
                            <option value="3">На контроль</option>
                            <option value="4">Выполнен</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Приоритет задачи</label>
                        <select name="type_id" id="addTask_type_id" class="form-control">
                            <option style="background: #ff0000; color: #000;" value="1">Высокий</option>
                            <option style="background: #92d050; color: #000;" value="3">Средний</option>
                            <option style="background: #9e9e9e; color: #FFF;" value="2">Низкий</option>

                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Наименование задачи</label>
                        <select name="number_request" id="addTask_number_request" class="form-control">
                            <option value="1">Связаться с клиентом</option>
                            <option value="13">Cвязаться с поставщиком</option>
                            <option value="3">Жду информацию от клиента</option>
                            <option value="4">Отправлено ТКП</option>
                            <option value="5">Отправлен счет на оплату</option>
                            <option value="6">Отправлен запрос поставщику</option>
                            <option value="7">Жду информацию от поставщика</option>
                            <option value="8">Жду оплату от клиента</option>
                            <option value="9">Жду оплату поставщику</option>
                            <option value="10">Разместить в производство</option>
                            <option value="11">Срочная отгрузка</option>
                            <option value="12">Отгрузка</option>
                            <option value="14">Связаться по возможным заявкам</option>
                            <option value="15">Проблемная отгрузка</option>
                            <option value="16">Тендер - запрос специалисту</option>
                            <option value="17">Тендер - ТКП</option>
                            <option value="18">Тендер - Жду результат</option>
                            <option value="19">Подготовить ТКП</option>
                            <option value="20">Подготовить Счет и договор</option>
                            <option value="21">Отправлен проект договора</option>
                        </select>
                    </div>
                    <input type="hidden" name="number_request1" id="addTask_number_request1" class="form-control">
                    <div class="form-group">
                        <label for="exampleInputEmail1">URL #1</label>
                        <input type="text" name="url_1" id="addTask_url1" class="form-control"
                               placeholder="http://example.com/index.php">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">URL #2</label>
                        <input type="text" name="url_2" id="addTask_url2" class="form-control"
                               placeholder="http://example.com/index.php">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Комментарий</label>
                        <textarea name="comment" id="addTask_comment" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="exampleInputEmail1">Время окончания задачи</label>
                        <div class="input-group mb-3">
                            <input name="complite_till" type='text' id='datetimepicker1' class="form-control"
                                   aria-describedby="basic-addon2" value="{{date('d.m.Y H:i')}}">
                            <div class="input-group-append" onclick="$('#datetimepicker1').datetimepicker('show');">
                                <span class="input-group-text" id="basic-addon2"><img width="24px"
                                                                                      src="{{url('/')}}/images/new/calendar-20px.svg"></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="addTask_notification">
                        <label class="form-check-label" for="addTask_notification">
                            Уведомить за 5 минут?
                        </label>
                    </div>

                </form>

            </div>
            <div class="modal-footer" style="
    display: block;
">
                <button type="button" class="btn btn-primary pull-left" onclick="addTask()">Добавить</button>
                <button type="button" class="btn btn-secondary pull-right" data-dismiss="modal" style="
    float: right;
">Отменить
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="changeStatus" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content" style="
    border-radius: 0px;
">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Редактирование задачи <span id="edit_nameTask_header">TEST</span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form role="form" action="{{url('/')}}/tasks/edit" method="post">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="user_id" id="edit_userId" value="{{$user['id']}}">
                    <input type="hidden" name="task_id" value="" id="edit_taskId">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Время окончания задачи</label>
                        <div class="input-group mb-3">
                            <input name="complite_till" type='text' id='datetimepicker2' class="form-control"
                                   aria-describedby="basic-addon2">
                            <div class="input-group-append" onclick="$('#datetimepicker2').datetimepicker('show');">
                                <span class="input-group-text" id="basic-addon2"><img width="24px"
                                                                                      src="{{url('/')}}/images/new/calendar-20px.svg"></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="edit_notification">
                        <label class="form-check-label" for="edit_notification">
                            Уведомить за 5 минут? <span id="edit_notification_text"></span>
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Воронка</label>
                        <select name="pipeline_id" id="edit_pipelineId" class="form-control">
                            <option value="1">Новая заявка</option>
                            <option value="2">Подбор поставщика</option>
                            <option value="3">На контроль</option>
                            <option value="4">Выполнен</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Приоритет задачи</label>
                        <select name="type_id" id="edit_typeId" class="form-control">
                            <option style="background: #ff0000; color: #000;" value="1">Высокий</option>
                            <option style="background: #92d050; color: #000;" value="3">Средний</option>
                            <option style="background: #9e9e9e; color: #FFF;" value="2">Низкий</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Наименование задачи</label>
                        <select name="number_request" class="form-control" id="edit_numberRequest">
                            <option value="1">Связаться с клиентом</option>
                            <option value="13">Cвязаться с поставщиком</option>
                            <option value="3">Жду информацию от клиента</option>
                            <option value="4">Отправлено ТКП</option>
                            <option value="5">Отправлен счет на оплату</option>
                            <option value="6">Отправлен запрос поставщику</option>
                            <option value="7">Жду информацию от поставщика</option>
                            <option value="8">Жду оплату от клиента</option>
                            <option value="9">Жду оплату поставщику</option>
                            <option value="10">Разместить в производство</option>
                            <option value="11">Срочная отгрузка</option>
                            <option value="12">Отгрузка</option>
                            <option value="14">Связаться по возможным заявкам</option>
                            <option value="15">Проблемная отгрузка</option>
                            <option value="16">Тендер - запрос специалисту</option>
                            <option value="17">Тендер - ТКП</option>
                            <option value="18">Тендер - Жду результат</option>
                            <option value="19">Подготовить ТКП</option>
                            <option value="20">Подготовить Счет и договор</option>
                            <option value="21">Отправлен проект договора</option>
                        </select>

                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">URL #1</label>
                        <input type="text" name="url_1" class="form-control" id="edit_url1"
                               placeholder="http://example.com/index.php">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">URL #2</label>
                        <input type="text" name="url_2" class="form-control" id="edit_url2"
                               placeholder="http://example.com/index.php">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Комментарий</label>
                        <textarea name="comment" id="edit_comment" class="form-control" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer" style="
    display: block;
">
                <button type="button" class="btn btn-primary pull-left" onclick="editTask()">Отредактировать</button>
                <button type="button" class="btn btn-secondary " data-dismiss="modal" style="
    float: right;
">Отменить
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="additionalInfo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Дополнительная информация</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tr>
                        <td colspan="2">Покупатель</td>
                    </tr>
                    <tr>
                        <td>Наименование компании</td>
                        <td id="info_nameCompany">Имя</td>
                    </tr>
                    <tr>
                        <td>Контактое лицо</td>
                        <td id="info_nameContact">Имя</td>
                    </tr>
                    <tr>
                        <td>Телефон</td>
                        <td id="info_telphone">Имя</td>
                    </tr>
                    <tr>
                        <td>E-Mail</td>
                        <td id="info_email">Имя</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="endTask" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Завершить дело </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center" style="
    font-size: 18px;
">Вы уверены что хотите завершить дело? <br><span id="endTask_task_name" style="
    font-weight: bold;
"></span><br><span
                            id="endTask_task_subname" style="
    font-weight: bold;
"></span></div>
            </div>
            <div class="modal-footer" style="
    display: block;
">
                <button type="button" class="btn btn-danger" id="endTask_end_button">
                    Завершить
                </button>
                <button type="button" class="btn btn-primary" data-dismiss="modal" aria-label="Close" style="
    float: right;
">
                    Отмена
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addComment" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content" style="
    border-radius: 0px;
">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Добавление комментария для <span
                            id="addComment_nameTask_header">TEST</span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="task_id" value="" id="addComment_taskId">
                <div class="form-group">
                    <label for="exampleInputEmail1">Комментарий</label>
                    <textarea name="comment" id="addComment_comment" class="form-control" rows="3"></textarea>
                </div>

            </div>
            <div class="modal-footer" style="
    display: block;
">
                <button type="button" class="btn btn-primary pull-left" onclick="addComment()">Добавить</button>
                <button type="button" class="btn btn-secondary " data-dismiss="modal" style="
    float: right;
">Отменить
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="miniEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content" style="
    border-radius: 0px;
">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Быстрое редактирование задачи <span
                            id="miniEdit_nameTask_header">TEST</span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="task_id" value="" id="miniEdit_taskId">
                <div class="form-group">
                    <label for="exampleInputEmail1">Время окончания задачи</label>
                    <div class="input-group mb-3">
                        <input name="complite_till" type='text' id='datetimepicker3' class="form-control"
                               aria-describedby="basic-addon2">
                        <div class="input-group-append" onclick="$('#datetimepicker3').datetimepicker('show');">
                                <span class="input-group-text" id="basic-addon2"><img width="24px"
                                                                                      src="{{url('/')}}/images/new/calendar-20px.svg"></span>
                        </div>
                    </div>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="miniEdit_notification">
                    <label class="form-check-label" for="miniEdit_notification">
                        Уведомить за 5 минут? <span id="miniEdit_notification_text"></span>
                    </label>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Комментарий</label>
                    <textarea name="comment" id="miniEdit_comment" class="form-control" rows="3"></textarea>
                </div>

            </div>
            <div class="modal-footer" style="
    display: block;
">
                <button type="button" class="btn btn-primary pull-left" onclick="miniEdit()">Отредактировать</button>
                <button type="button" class="btn btn-secondary " data-dismiss="modal" style="
    float: right;
">Отменить
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="setIntervalMenuModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content" style="
    border-radius: 0px;
">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Выбор интервала
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="form-group">
                    <label for="exampleInputEmail1">Время окончания задачи ОТ</label>
                    <div class="input-group mb-3">
                        <input name="complite_till" type='text' id='datetimepicker4' class="form-control"
                               aria-describedby="basic-addon2">
                        <div class="input-group-append" onclick="$('#datetimepicker4').datetimepicker('show');">
                                <span class="input-group-text" id="basic-addon2"><img width="24px"
                                                                                      src="{{url('/')}}/images/icon_calendar.svg"></span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Время окончания задачи ДО</label>
                    <div class="input-group mb-3">
                        <input name="complite_till" type='text' id='datetimepicker5' class="form-control"
                               aria-describedby="basic-addon2">
                        <div class="input-group-append" onclick="$('#datetimepicker5').datetimepicker('show');">
                                <span class="input-group-text" id="basic-addon2"><img width="24px"
                                                                                      src="{{url('/')}}/images/icon_calendar.svg"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="
    display: block;
">
                <button type="button" class="btn btn-primary pull-left" onclick="setIntervalMenu()">Применить фильтр
                </button>
                <button type="button" class="btn btn-secondary " data-dismiss="modal" style="
    float: right;
">Отменить
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="notificationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Уведомления</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-fill nav-pills" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab"
                           aria-controls="pills-home" aria-selected="true">Активные</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab"
                           aria-controls="pills-profile" aria-selected="false">Завершенные</a>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                         aria-labelledby="pills-home-tab">
                        <div class="inner-pills">
                            <table class="table table-sm" id="notification_modal_active">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Задача</th>
                                    <th>Статус</th>
                                    <th>Время уведомления</th>
                                </tr>
                                </thead>
                                <tbody id="notification_modal_active_body">

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                        <div class="inner-pills">
                            <table class="table table-sm" id="notification_modal_expired">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Задача</th>
                                    <th>Статус</th>
                                    <th>Время уведомления</th>
                                </tr>
                                </thead>
                                <tbody id="notification_modal_expired_body">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="alert alert-success" role="alert" id="alert-success" style="width: 20%;
    float: right;
    bottom: 20px;
    right: 25px;
        margin: 0;
display: none">

</div>
<div class="alert alert-danger" role="alert" id="alert-danger" style="width: 20%;
    float: right;
    bottom: 20px;
    right: 25px;
        margin: 0;
display: none">

</div>
</html>