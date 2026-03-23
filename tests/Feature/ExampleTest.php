<?php

it('redirects guests from home to the landing page', function () {
    $this->get('/')->assertRedirect(route('landing'));
});
