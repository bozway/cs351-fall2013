				var CARD_WIDTH = 250;
				var currentCard=0;
				var maxCards=4;
				var speed=500;
				
				var content;
					
				var swipeOptions=
				{
					triggerOnTouchEnd : true,	
					swipeStatus : swipeStatus,
					allowPageScroll:"vertical",
					threshold:75			
					};
				
				$(function()
				{				
					content = $("#content");
					content.swipe( swipeOptions );
				});
			
					
				/**
				* Catch each phase of the swipe.
				* move : we drag the div.
				* cancel : we animate back to where we were
				* end : we animate to the next card
				*/			
				function swipeStatus(event, phase, direction, distance)
				{
                 
					//If we are moving before swipe, and we are going Lor R in X mode, or U or D in Y mode then drag.
					if( phase=="move" && (direction=="left" || direction=="right") )
					{
						var duration=0;
						
						if (direction == "left")
							scrollCards((CARD_WIDTH * currentCard) + distance, duration);
						
						else if (direction == "right")
							scrollCards((CARD_WIDTH * currentCard) - distance, duration);
						
					}
					
					else if ( phase == "cancel")
					{
						scrollCards(CARD_WIDTH * currentCard, speed);
					}
					
					else if ( phase =="end" )
					{
						if (direction == "right")
							previousCard();
						else if (direction == "left")			
							nextCard();
					}
				}
						
				
			
				function previousCard()
				{
					currentCard = Math.max(currentCard-1, 0);
					scrollCards( CARD_WIDTH * currentCard, speed);
				}
			
				function nextCard()
				{
					currentCard = Math.min(currentCard+1, maxCards-1);
					scrollCards( CARD_WIDTH * currentCard, speed);
				}
					
				/**
				* Manually update the position of the content on drag
				*/
				function scrollCards(distance, duration)
				{
					content.css("-webkit-transition-duration", (duration/1000).toFixed(1) + "s");
					
					//inverse the number we set in the css
					var value = (distance<0 ? "" : "-") + Math.abs(distance).toString();
					
					content.css("-webkit-transform", "translate3d("+value +"px,0px,0px)");
				}