<?php echo $this->session->flashdata('action_status'); ?>
	<div class="page-header" id="banner">
		<div id="page-status-message"></div>
        <div class="row">
          <div class="col-lg-12 col-md-12 col-sm-12">
            <h1>Page Title</h1>
            <p class="home-text">
				Bacon ipsum dolor amet chicken spare ribs t-bone fatback, boudin venison doner 
				pastrami brisket pork loin beef ribs pork chop bacon. Biltong boudin sirloin 
				buffalo t-bone salami alcatra sausage. Swine ribeye drumstick shankle short 
				ribs hamburger pork chop biltong short loin. Pork short loin rump salami 
				tenderloin andouille pork belly. Chislic short ribs ham hock alcatra, meatloaf 
				ham porchetta pork belly. Frankfurter chislic tongue burgdoggen beef. Leberkas 
				andouille cow corned beef.
			</p>
			<p class="home-text">
				Brisket meatball bresaola short loin spare ribs picanha ham hock rump corned beef 
				capicola biltong. Biltong corned beef brisket ribeye, frankfurter pastrami sirloin. 
				T-bone biltong doner bacon pig kielbasa beef ribs pork chop ball tip flank bresaola 
				jerky, leberkas tenderloin. Tongue shankle swine picanha strip steak ham hock short 
				ribs tenderloin beef ribeye. Meatloaf prosciutto cow, short ribs flank corned beef 
				landjaeger bresaola alcatra shoulder boudin ground round beef andouille leberkas.
			</p>
          </div>
        </div>
    </div>


	<script>
    $(document).ready(function() {

		$('#input-id').on('click', function (e) {
			e.preventDefault();

			if ($('#input-id').val() === '') {
				alert('Please enter an input value...');
				return false;
			}

			// Variables
			var id = '33';
			var success_url = '<?php echo current_url(); ?>';


			$.ajax({
				url: "<?php echo site_url('controller/route'); ?>",
				type: 'post',
				data: {
					'id': id
				},
				success: function(response) {
					var obj = $.parseJSON(response);
					if (obj.status === 'success') {
						window.location.replace(success_url);
					} else {
						$('#page-status-message').html(obj.message);
					}
				},
				failure: function(msg) {
					alert('error');
				}
			});
		});
	});
</script>