<footer>

    <div class="foot-grid">

        <div>

            <div
                class="logo-text"
                style="margin-bottom:12px;"
            >

                Shubh<span style="color:#FBD599;">
                    Samagri
                </span>

            </div>

            <p style="max-width:260px;">
                Puja samagri aur wedding items —
                local shehar mein same-day,
                poore Bharat mein shipping ke saath.
            </p>

        </div>


        <div>

            <h4>Categories</h4>

            <a href="puja-samagri.php">
                Puja Samagri
            </a>

            <a href="wedding-items.php">
                Wedding Items
            </a>

            <a href="services.php">
                Services
            </a>

        </div>


        <div>

            <h4>Company</h4>

            <a href="index.php">
                Home
            </a>

            <a href="contact.php">
                Contact
            </a>

        </div>


        <div>

            <h4>Contact</h4>

            <a href="#">
                WhatsApp: +91 00000 00000
            </a>

            <a href="#">
                hello@shubhsamagri.com
            </a>

            <a href="#">
                Local city + Pan-India
            </a>

        </div>

    </div>


    <div class="foot-bottom">

        <span>
            © <?= date('Y') ?> ShubhSamagri.
        </span>

        <span>
            Made for aapka business
        </span>

    </div>

</footer>

<script src="cart.js"></script>

<?php if (!empty($pageScripts)): ?>
    <?= $pageScripts ?>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const wrapper = document.querySelector('.floating-puja-whatsapp');
    const button = document.getElementById('floatingWaButton');
    const close = document.getElementById('floatingWaClose');

    if (!wrapper || !button || !close) return;


    button.addEventListener('click', function () {

        const isOpen = wrapper.classList.toggle('open');

        button.setAttribute(
            'aria-expanded',
            isOpen ? 'true' : 'false'
        );

    });


    close.addEventListener('click', function () {

        wrapper.classList.remove('open');

        button.setAttribute(
            'aria-expanded',
            'false'
        );

    });


    document.addEventListener('click', function (event) {

        if (!wrapper.contains(event.target)) {

            wrapper.classList.remove('open');

            button.setAttribute(
                'aria-expanded',
                'false'
            );

        }

    });

});
</script>

</body>
</html>