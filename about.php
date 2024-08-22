<?php include 'header.php'; ?>
<?php include 'logger.php'; ?>
<style>
    body{
        background-image: url('images/5.jpg');
        background-size: cover;
    }
        .about-section {
        background-position: center;
        padding: 50px;
        color: white;
        text-align: center;
        background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent background color */
        border-radius: 10px;
        margin: 40px;
        margin-left: 110px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .about-section h1 {
        font-size: 36px;
        margin-bottom: 20px;
        color: orange;
    }
    .about-section p {
        font-size: 18px;
        line-height: 1.6;
        margin-bottom: 20px;
    }

    @media (max-width: 768px) {
            th, td {
                padding: 10px;
                font-size: 14px;
            }
            a {
                width: 100%;
                padding: 15px 20px;
            }
        }
        @media (max-width: 480px) {
            th, td {
                padding: 8px;
                font-size: 12px;
            }
            a {
                width: 100%;
                padding: 12px 15px;
            }
        }

</style>
<div class="about-section">
    <h1>About Us</h1>
    <p>Welcome to Watch Haven, your number one source for all things watches. We're dedicated to giving you the very best of watches, with a focus on dependability, customer service, and uniqueness.</p>
    <p>Founded in 2024, Watch Haven has come a long way from its beginnings. When we first started out, our passion for premium watches drove us to do tons of research so that Watch Haven can offer you the world's most advanced watches. We now serve customers all over the world, and are thrilled that we're able to turn our passion into our own website.</p>
    <p>We hope you enjoy our products as much as we enjoy offering them to you. If you have any questions or comments, please don't hesitate to contact us.</p>
    <p>Sincerely,<br>Watch Haven Team</p>
</div>

<?php include 'footer.php'; ?>
