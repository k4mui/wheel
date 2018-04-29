function subscription_callback() {
    if (this.readyState==4) {
        if (this.status==200) {
            var reply = JSON.parse(this.responseText);
            if (!reply.error[0]) {
                ID('subscription').setAttribute('data-state', reply.data.state);
            }
        }
    }
}

function subscription_handler(e) {
    if (e.srcElement.hasAttribute('data-state')) {
        var state = e.srcElement.getAttribute('data-state');
        var category_id = e.srcElement.getAttribute('data-category-id');
        if (state==='subscribed') {
            api('subscription', {
                'action': -1,
                'category_id': category_id
            }, subscription_callback);
        } else if (state==='unsubscribed') {
            api('subscription', {
                'action': 1,
                'category_id': category_id
            }, subscription_callback);
        } else {
            api('subscription', {
                'action': 0,
                'category_id': category_id
            }, subscription_callback);
        }
    }
}