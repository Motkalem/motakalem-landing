
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="UqNdSYnR64wn8bQ1m6X4eVBMgQ0oW1X7AWWJxU6n" />
    <title>تسجيل الدخول :: Motkalem</title>


    <link rel="stylesheet" type="text/css" href="http://127.0.0.1:8000/packages/backpack/base/css/bundle.css?v=5.6.1@1b67f8efdbaa48842e0d31995068b44b8fc5c66d">
    <link rel="stylesheet" type="text/css" href="http://127.0.0.1:8000/packages/source-sans-pro/source-sans-pro.css?v=5.6.1@1b67f8efdbaa48842e0d31995068b44b8fc5c66d">
    <link rel="stylesheet" type="text/css" href="http://127.0.0.1:8000/packages/line-awesome/css/line-awesome.min.css?v=5.6.1@1b67f8efdbaa48842e0d31995068b44b8fc5c66d">






    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="app flex-row align-items-center">


<div class="container">
    <div class="row justify-content-center">

        @yield('content')
    </div>
</div>

<footer class="app-footer sticky-footer">
    <div class="text-muted ml-auto mr-auto">
        صنع بيد <a target="_blank" rel="noopener" href="squarement.sa">Squarement</a>.
    </div>
</footer>


<script type="text/javascript" src="http://127.0.0.1:8000/packages/backpack/base/js/bundle.js?v=5.6.1@1b67f8efdbaa48842e0d31995068b44b8fc5c66d"></script>



<script type="text/javascript">
    // This is intentionaly run after dom loads so this way we can avoid showing duplicate alerts
    // when the user is beeing redirected by persistent table, that happens before this event triggers.
    document.onreadystatechange = function() {
        if (document.readyState == "interactive") {
            Noty.overrideDefaults({
                layout: 'topRight',
                theme: 'backstrap',
                timeout: 2500,
                closeWith: ['click', 'button'],
            });

            // get alerts from the alert bag
            var $alerts_from_php = [];

            // get the alerts from the localstorage
            var $alerts_from_localstorage = JSON.parse(localStorage.getItem('backpack_alerts')) ?
                JSON.parse(localStorage.getItem('backpack_alerts')) : {};

            // merge both php alerts and localstorage alerts
            Object.entries($alerts_from_php).forEach(function(type) {
                if (typeof $alerts_from_localstorage[type[0]] !== 'undefined') {
                    type[1].forEach(function(msg) {
                        $alerts_from_localstorage[type[0]].push(msg);
                    });
                } else {
                    $alerts_from_localstorage[type[0]] = type[1];
                }
            });

            for (var type in $alerts_from_localstorage) {
                let messages = new Set($alerts_from_localstorage[type]);

                messages.forEach(function(text) {
                    let alert = {};
                    alert['type'] = type;
                    alert['text'] = text;
                    new Noty(alert).show()
                });
            }

            // in the end, remove backpack alerts from localStorage
            localStorage.removeItem('backpack_alerts');
        }
    };
</script>


<script type="text/javascript">
    // To make Pace works on Ajax calls
    $(document).ajaxStart(function() { Pace.restart(); });

    // polyfill for `startsWith` from https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/String/startsWith
    if (!String.prototype.startsWith) {
        Object.defineProperty(String.prototype, 'startsWith', {
            value: function(search, rawPos) {
                var pos = rawPos > 0 ? rawPos|0 : 0;
                return this.substring(pos, pos + search.length) === search;
            }
        });
    }



    // polyfill for entries and keys from https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Object/entries#polyfill
    if (!Object.keys) Object.keys = function(o) {
        if (o !== Object(o))
            throw new TypeError('Object.keys called on a non-object');
        var k=[],p;
        for (p in o) if (Object.prototype.hasOwnProperty.call(o,p)) k.push(p);
        return k;
    }

    if (!Object.entries) {
        Object.entries = function( obj ){
            var ownProps = Object.keys( obj ),
                i = ownProps.length,
                resArray = new Array(i); // preallocate the Array
            while (i--)
                resArray[i] = [ownProps[i], obj[ownProps[i]]];
            return resArray;
        };
    }

    // Ajax calls should always have the CSRF token attached to them, otherwise they won't work
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    var activeTab = $('[href="' + location.hash.replace("#", "#tab_") + '"]');
    location.hash && activeTab && activeTab.tab('show');
    $('.nav-tabs a').on('shown.bs.tab', function (e) {
        location.hash = e.target.hash.replace("#tab_", "#");
    });
</script>

<script>
    $(document).ajaxComplete((e, result, settings) => {
        if(result.responseJSON?.exception !== undefined) {
            $.ajax({...settings, accepts: "text/html", backpackExceptionHandler: true});
        }
        else if(settings.backpackExceptionHandler) {
            Noty.closeAll();
            showErrorFrame(result.responseText);
        }
    });

    const showErrorFrame = html => {
        let page = document.createElement('html');
        page.innerHTML = html;
        page.querySelectorAll('a').forEach(a => a.setAttribute('target', '_top'));

        let modal = document.getElementById('ajax-error-frame');

        if (typeof modal !== 'undefined' && modal !== null) {
            modal.innerHTML = '';
        } else {
            modal = document.createElement('div');
            modal.id = 'ajax-error-frame';
            modal.style.position = 'fixed';
            modal.style.width = '100vw';
            modal.style.height = '100vh';
            modal.style.padding = '5vh 5vw';
            modal.style.backgroundColor = 'rgba(0, 0, 0, 0.4)';
            modal.style.zIndex = 200000;
        }

        let iframe = document.createElement('iframe');
        iframe.style.backgroundColor = '#17161A';
        iframe.style.borderRadius = '5px';
        iframe.style.width = '100%';
        iframe.style.height = '100%';
        iframe.style.border = '0';
        iframe.style.boxShadow = '0 0 4rem';
        modal.appendChild(iframe);

        document.body.prepend(modal);
        document.body.style.overflow = 'hidden';
        iframe.contentWindow.document.open();
        iframe.contentWindow.document.write(page.outerHTML);
        iframe.contentWindow.document.close();

        // Close on click
        modal.addEventListener('click', () => hideErrorFrame(modal));

        // Close on escape key press
        modal.setAttribute('tabindex', 0);
        modal.addEventListener('keydown', e => e.key === 'Escape' && hideErrorFrame(modal));
        modal.focus();
    }

    const hideErrorFrame = modal => {
        modal.outerHTML = '';
        document.body.style.overflow = 'visible';
    }
</script>

</body>
</html>
