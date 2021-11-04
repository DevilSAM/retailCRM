$(document).on('click', '.navbar-toggler', function () {
    toggle_menu();
})
    .on('click', '.sidebar_opened .main_page', function () {
        var body = $('body');
        if (body.hasClass('sidebar_opened')) {
            toggle_menu();
        }
    })

    .on('click', '.scroll_spy_menu a', function () {
        event.preventDefault();
        var id = $(this).attr('href');

        $('html, body').animate({
            scrollTop: $(id).offset().top
        }, 700);


    })

    // Обрабатываем поля ввода
    .on('keyup', '.stacked_input input, .stacked_input textarea', function () {
        var input = $(this);
        if (input.val().length > 0 && !input.hasClass('filled')) {
            input.addClass('filled');
        } else if (input.val().length < 1) {
            input.removeClass('filled');
        }
    })
;

// Открытие/закрытие меню для мобильной версии
function toggle_menu()
{
    var body = $('body');
    if (body.hasClass('sidebar_opened')) {
        body.removeClass('sidebar_opened');
        setTimeout(function () {
            $('.top_nav .navbar-toggle').removeClass('toggled');
        }, 500);

    } else {
        body.addClass('sidebar_opened');
        setTimeout(function () {
            $('.top_nav .navbar-toggle').addClass('toggled');
        }, 500);
    }
}


$(document).ready(function () {
    $('.selectpicker').selectpicker({
        noneSelectedText: 'Ничего не выбрано',
    });

    $('.stacked_input input').each(function (e, val) {
        if ($(this).val().length > 0) {
            $(this).addClass('filled');
        }
    });

});


$(function () {
    $(window).scroll(function () {
        if ($(this).scrollTop() > 300) {
            $('.to-top').removeClass('to-top_hidden');
        } else {
            $('.to-top').addClass('to-top_hidden');
        }
        var object_form_list = $('#object_form_list');
        if (object_form_list.length > 0) {
            if ($(this).scrollTop() > object_form_list.offset().top) {
                $('.scroll_spy_menu').addClass('scroll_spy_fixed');
            } else {
                $('.scroll_spy_menu').removeClass('scroll_spy_fixed');
            }
        }
    });

    $('.to-top').click(function (event) {
        event.preventDefault();
        $('body,html').animate({scrollTop: 0}, 800);
    });

    $('[data-toggle="popover"]').popover();
});


window.pluralize = function (number, one, two, five) {
    let n = Math.abs(number);
    n %= 100;
    if (n >= 5 && n <= 20) {
        return five;
    }
    n %= 10;
    if (n === 1) {
        return one;
    }
    if (n >= 2 && n <= 4) {
        return two;
    }
    return five;
};

Vue.filter('formatSize', function (size) {
    if (size > 1024 * 1024 * 1024 * 1024) {
        return (size / 1024 / 1024 / 1024 / 1024).toFixed(2) + ' TB'
    } else if (size > 1024 * 1024 * 1024) {
        return (size / 1024 / 1024 / 1024).toFixed(2) + ' GB'
    } else if (size > 1024 * 1024) {
        return (size / 1024 / 1024).toFixed(2) + ' MB'
    } else if (size > 1024) {
        return (size / 1024).toFixed(2) + ' KB'
    }
    return size.toString() + ' B'
})
