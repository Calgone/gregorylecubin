/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


function test() {
    alert('test');
}

function infoLabel(typClass, msg, time) {
    //affiche un message en haut de la fenêtre pour donner des infos
    //typClass (bootstrap) success, warning, info, important, inverse
    //msg : message à afficher dans le div
    // time : en milliseconds avant de cacher le div
    $('#spanInfo').addClass("label-" + typClass);
    $('#spanInfo').html(msg);
    $('#divInfo').show();
    setTimeout(function() {
        $('#divInfo').hide();
    }, time);
}

function readerItemMarkRead(itemId, url) {

//    $.ajax({
//        type: 'GET',
//        url: 'reader/markread',
//        datatype: 'json',
//        data: {id : id},
//        success: function(response){
//            console.log('réponse : ');
//            console.log(response.code);
//            if (response.code === 100) {
//                $('#itemTitle' + id ).css("font-weight", "normal")
//            }
//        }
//    }).fail(function() { 
//                infoLabel('warning', 'Oops! Un problème est survenu...', 10000);
//                });

    if ($('#itemTitle' + itemId).css('font-weight') === 'bold') {
        $.get(url,
                {id: itemId})
                .done(function(response) {
            if (response.code === 100) {
                $('#itemTitle' + itemId).css("font-weight", "normal");
                $('#itemTitle' + itemId).closest('tr').css('background-color', '#f5f5f5');
            }
            if (response.channelId !== null) {
                var count = parseInt(
                        $('#channel' + response.channelId + 'UnreadCount').html() - 1);
                $('#channel' + response.channelId + 'UnreadCount').html(count);
            }
        })
                .fail(function() {
            infoLabel('warning', 'Oops! Un problème est survenu...', 10000);
        });
    }
}

function readerItemsMarkRead(url) {
    $.get(url,
            {})
            .done(function(response) {
        if (response.code === 100) {
            $('.aListItem').css("font-weight", "normal");
            $('.trListItem').css("background-color", "#f5f5f5");
            $('.channelUnreadCount').each(function() {
                $(this).html('0');
            });
        }
    })
            .fail(function() {
        infoLabel('warning', 'Oops! Un problème est survenu...', 10000);
    });
}

function readerChannelMarkRead(url) {
    $.get(url,
            {})
            .done(function(response) {
        if (response.code === 100) {
            $('.aListItem').css("font-weight", "normal");
            $('.trListItem').css("background-color", "#f5f5f5");
            $('#channel' + response.channelId + 'UnreadCount').html('0');
        }
    })
            .fail(function() {
        infoLabel('warning', 'Oops! Un problème est survenu...', 10000);
    });
}

function readerUpdateUnreadCount(channelId, unreadCount) {
    unreadCount = unreadCount || 0;

    var spanUnreadCount = $('#channel' + channelId + 'UnreadCount');
    unreadCount = parseInt(spanUnreadCount.html() - 1);
    spanUnreadCount.html(unreadCount);

}
