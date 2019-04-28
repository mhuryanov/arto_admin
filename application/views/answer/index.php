<script>
    window.onmessage = (event) => {
        console.log(event.data);
        location.href = "<?=base_url()?>postAnswers/" + event.data.userId;
    };
</script>

