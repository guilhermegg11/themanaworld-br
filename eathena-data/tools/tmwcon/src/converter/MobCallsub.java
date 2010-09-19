package converter;

public class MobCallsub implements Comparable<Object> {

	private String callsub;
	private String args;

	public MobCallsub() {}

	public int compareTo(Object obj) {
		if( obj instanceof MobCallsub )
			return getCallsub().compareTo( ((MobCallsub)obj).getCallsub() );
		else if( obj instanceof String )
			return getCallsub().compareTo( (String)obj );
		return 0;
	}

	public String getCallsub() {
		return callsub;
	}

	public void setCallsub(String callsub) {
		this.callsub = callsub;
	}

	public String getArgs() {
		return args;
	}

	public void setArgs(String args) {
		this.args = args;
	}

}
