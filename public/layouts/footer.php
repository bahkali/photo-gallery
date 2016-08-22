</div>
<div id="footer">
        Copyright <?php echo date("Y", time());?>, By mamadou Kali Bah
    </div>
</body>
</html>
<?php if(isset($database)) {$database->close_connection();} ?>