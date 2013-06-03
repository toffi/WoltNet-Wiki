<article class="message messageReduced">
	<div>
		<section class="messageContent">
			<div>
				<header class="messageHeader">
					<div class="messageCredits box32">
						<a
							href="{link controller='User' object=$article->getUserProfile()->getDecoratedObject()}{/link}"
							class="framed">{@$article->getUserProfile()->getAvatar()->getImageTag(32)}</a>
						<div>
							<p>
								<a
									href="{link controller='User' object=$article->getUserProfile()->getDecoratedObject()}{/link}">{$article->getUsername()}</a>
							<p>{@$article->time|time}
						</div>
					</div>

					<h1 class="messageTitle">{$article->getTitle()}</h1>
				</header>

				<div class="messageBody">
					<div>
						<div class="messageText">{@$article->getFormattedMessage()}
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
</article>