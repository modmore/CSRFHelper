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

## Usage

After installing the extra, you get access to some new snippets: 

- `csrfhelper` returns a CSRF token that you need to insert into the form (typically as a hidden input with name `csrf_token`) that is submitted along with the request.
- `csrfhelper_formit` is meant to be used as a FormIt hook to validate the `csrf_token` variable.

### FormIt example

In the FormIt snippet call, add the `csrfhelper_formit` hook. Also add a `&csrfKey` property with the key for the CSRF token; this should be unique for each unique form and match the `&key` in the `csrfhelper` snippet call.

Next, in your form, add the following:

     <input type="hidden" name="csrf_token" value="[[!csrfhelper? &key=`simple-form`]]">

Adjust the `&key` to match the `&csrfKey` property in your FormIt snippet. 

For sensitive forms, you can also add a `&singleUse` property with value 1 that ensures each request gets a unique CSRF token. If you leave this out, the token for the form is the same for up to 24 hours. 

To show the error when the CSRF token does not match, or if it can't be securely generated on your server, add:

     [[!+fi.error.csrf_token:notempty=`<div class="error">[[!+fi.error.csrf_token]]</div>`]]

Below is a full example based on the [simple contact form example for FormIt](https://docs.modx.com/extras/revo/formit/formit.tutorials-and-examples/formit.examples.simple-contact-page).

```` html
[[!FormIt?
   &hooks=`spam,csrfhelper_formit,email,redirect`
   &redirectTo=`71`
   &validate=`nospam:blank,
      name:required,
      email:email:required,
      subject:required,
      text:required:stripTags`
   &csrfKey=`simple-form`
]]


<h2>Contact Form</h2>
 
[[!+fi.validation_error_message:notempty=`<p>[[!+fi.validation_error_message]]</p>`]]
 
<form action="[[~[[*id]]]]" method="post" class="form">
     [[!+fi.error.csrf_token:notempty=`<div class="error">[[!+fi.error.csrf_token]]</div>`]]
     <input type="hidden" name="csrf_token" value="[[!csrfhelper? &key=`simple-form`]]">
    <input type="hidden" name="nospam" value="" />

    <label for="name">
        Name:
        <span class="error">[[!+fi.error.name]]</span>
    </label>
    <input type="text" name="name" id="name" value="[[!+fi.name]]" />

    <label for="email">
        Email:
        <span class="error">[[!+fi.error.email]]</span>
    </label>
    <input type="text" name="email" id="email" value="[[!+fi.email]]" />
 
    <label for="subject">
        Subject:
        <span class="error">[[!+fi.error.subject]]</span>
    </label>
    <input type="text" name="subject" id="subject" value="[[!+fi.subject]]" />
 
    <label for="text">
        Message:
        <span class="error">[[!+fi.error.text]]</span>
    </label>
    <textarea name="text" id="text" cols="55" rows="7" value="[[!+fi.text]]">[[!+fi.text]]</textarea>
  
    <div class="form-buttons">
        <input type="submit" value="Send Contact Inquiry" />
    </div>
</form>
````

