<head>
<title>Twitter Bootstrap Ordered list Example</title>\
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<style>
    body {
        background: #333 }
    div.container {
        background: #444141;
    	color: #d9edf7;
    	font-size: 20px;
    	line-height: 2;
        border-radius: 1em;
        padding: 1em;
        width: 800px;
        position: absolute;
        top: 50%;
        left: 50%;
        margin-right: -50%;
        transform: translate(-50%, -50%) }
</style>
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<ol>
					<?php foreach($data as $status): ?>
						<li><?php echo $status; ?></li>
        			<?php endforeach; ?>
				</ol>
			</div>
		</div>
	</div>
</body>