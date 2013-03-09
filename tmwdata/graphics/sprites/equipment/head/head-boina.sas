<?xml version="1.0"?>
<sprite name="player" action="stand">

	<imageset name="base" src="graphics/sprites/equipment/head/head-boina.png|W" width="32" height="32"/>

	<action name="stand" imageset="base">
		<animation direction="down">
			<frame index="0" offsetX="1" offsetY="-35"/>
		</animation>
		<animation direction="left">
			<frame index="1" offsetX="-2" offsetY="-35"/>
		</animation>
		<animation direction="up">
			<frame index="2" offsetX="-2" offsetY="-35"/>
		</animation>
		<animation direction="right">
			<frame index="3" offsetX="0" offsetY="-35"/>
		</animation>
	</action>

	<action name="walk" imageset="base">
		<animation direction="down">
			<frame index="0" delay="75"	offsetX="1" offsetY="-34"/>
			<frame index="0" delay="150"	offsetX="1" offsetY="-35"/>
			<frame index="0" delay="75"	offsetX="1" offsetY="-34"/>
			<frame index="0" delay="150"	offsetX="1" offsetY="-35"/>
		</animation>
		<animation direction="left">
			<frame index="1" delay="75"	offsetX="-2" offsetY="-36"/>
			<frame index="1" delay="75"	offsetX="-2" offsetY="-35"/>
			<frame index="1" delay="150"	offsetX="-2" offsetY="-36"/>
			<frame index="1" delay="75"	offsetX="-2" offsetY="-35"/>
			<frame index="1" delay="75"	offsetX="-2" offsetY="-36"/>
		</animation>
		<animation direction="up">
			<frame index="2" delay="75"	offsetX="-2" offsetY="-36"/>
			<frame index="2" delay="150"	offsetX="-2" offsetY="-37"/>
			<frame index="2" delay="75"	offsetX="-2" offsetY="-36"/>
			<frame index="2" delay="150"	offsetX="-2" offsetY="-37"/>
		</animation>
		<animation direction="right">
			<frame index="3" delay="75"	offsetX="0" offsetY="-36"/>
			<frame index="3" delay="75"	offsetX="0" offsetY="-35"/>
			<frame index="3" delay="150"	offsetX="0" offsetY="-36"/>
			<frame index="3" delay="75"	offsetX="0" offsetY="-35"/>
			<frame index="3" delay="75"	offsetX="0" offsetY="-36"/>
		</animation>
	</action>

	<action name="sit" imageset="base">
		<animation direction="down">
			<frame index="0" offsetX="1" offsetY="-24"/>
		</animation>
		<animation direction="left">
			<frame index="1" offsetX="2" offsetY="-27"/>
		</animation>
		<animation direction="up">
			<frame index="2" offsetX="-1" offsetY="-28"/>
		</animation>
		<animation direction="right">
			<frame index="3" offsetX="-7" offsetY="-27"/>
		</animation>
	</action>

	<action name="attack" imageset="base">
		<animation direction="down">
			<frame index="0" delay="75" offsetX="0" offsetY="-34"/>
			<frame index="0" delay="75" offsetX="0" offsetY="-35"/>
			<frame index="0" delay="75" offsetX="0" offsetY="-32"/>
			<frame index="0" delay="75" offsetX="0" offsetY="-31"/>
			<end/>
		</animation>
		<animation direction="left">
			<frame index="1" delay="75" offsetX="2" offsetY="-37"/>
			<frame index="1" delay="75" offsetX="2" offsetY="-38"/>
			<frame index="1" delay="75" offsetX="0" offsetY="-35"/>
			<frame index="1" delay="75" offsetX="-2" offsetY="-34"/>
			<end/>
		</animation>
		<animation direction="up">
			<frame index="2" delay="225" offsetX="-2" offsetY="-36"/>
			<frame index="2" delay="75" offsetX="-2" offsetY="-34"/>
			<end/>
		</animation>
		<animation direction="right">
			<frame index="3" delay="75" offsetX="-3" offsetY="-37"/>
			<frame index="3" delay="75" offsetX="-3" offsetY="-38"/>
			<frame index="3" delay="75" offsetX="0" offsetY="-35"/>
			<frame index="3" delay="75" offsetX="1" offsetY="-34"/>
			<end/>
		</animation>
	</action>

	<action name="attack_bow" imageset="base">
		<animation direction="down">
			<frame index="0" delay="225"	offsetX="1" offsetY="-34"/>
			<frame index="0" delay="75"	offsetX="1" offsetY="-33"/>
			<frame index="0" delay="75"	offsetX="1" offsetY="-34"/>
			<end/>
		</animation>
		<animation direction="left">
			<frame index="1" delay="225" offsetX="2" offsetY="-36"/>
			<frame index="1" delay="150" offsetX="4" offsetY="-37"/>
			<end/>
		</animation>
		<animation direction="up">
			<frame index="2" delay="150" offsetX="-2" offsetY="-36"/>
			<frame index="2" delay="75" offsetX="-3" offsetY="-36"/>
			<frame index="2" delay="75" offsetX="-3" offsetY="-35"/>
			<frame index="2" delay="75" offsetX="-3" offsetY="-36"/>
			<end/>
		</animation>
		<animation direction="right">
			<frame index="3" delay="225" offsetX="-3" offsetY="-36"/>
			<frame index="3" delay="150" offsetX="-5" offsetY="-37"/>
			<end/>
		</animation>
	</action>

	<action name="dead" imageset="base">
		<animation direction="default">
			<frame index="4" offsetX="18" offsetY="-10"/>
		</animation>
	</action>

</sprite>
