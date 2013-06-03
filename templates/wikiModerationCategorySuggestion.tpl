<article class="message messageReduced">
	<div>
		<section class="messageContent">
			<div>
				<header class="messageHeader">
					<div class="messageCredits box32">
						<a
							href="{link controller='User' object=$categorySuggestion->getUserProfile()->getDecoratedObject()}{/link}"
							class="framed">{@$categorySuggestion->getUserProfile()->getAvatar()->getImageTag(32)}</a>
						<div>
							<p>
								<a
									href="{link controller='User' object=$categorySuggestion->getUserProfile()->getDecoratedObject()}{/link}">{$categorySuggestion->getUsername()}</a>
							<p>{@$categorySuggestion->time|time}
						</div>
					</div>

					<h1 class="messageTitle">{$categorySuggestion->getTitle()}</h1>
				</header>

				<div class="messageBody">
					<div>
						<div class="messageText">{$categorySuggestion->getReason()}
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
</article>