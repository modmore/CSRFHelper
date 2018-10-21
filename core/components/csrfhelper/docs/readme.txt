CSRF Helper
-----------

CSRF Helper is a tool for MODX to secure forms against cross-site request forgery (CSRF) vulnerabilities.

The Open Web Application Security Project (OWASP) explains CSRF as follows:

    Cross-Site Request Forgery (CSRF) is an attack that forces an end user to
    execute unwanted actions on a web application in which they're currently authenticated.
    CSRF attacks specifically target state-changing requests, not theft of data, since the
    attacker has no way to see the response to the forged request.

    With a little help of social engineering (such as sending a link via email or chat),
    an attacker may trick the users of a web application into executing actions of the
    attacker's choosing.

    If the victim is a normal user, a successful CSRF attack can force the user to perform
    state changing requests like transferring funds, changing their email address, and so forth.
    If the victim is an administrative account, CSRF can compromise the entire web application.

    https://www.owasp.org/index.php/Cross-Site_Request_Forgery_(CSRF)

On a MODX site, areas that may require protection against CSRF include:

- Contact forms
- Login, registration, profile update
- Checkout

CSRF Helper can help!

Brought to you by modmore (modmore.com).
