package converter;

public class MobContagem implements Comparable<Object> {

	private int idMob;
	private String callsub;
	private String _return;
	private String varMobs;
	private String varFlag;
	private Integer max;
	
	public MobContagem() {}

	public int compareTo(Object obj) {
		if( obj instanceof MobContagem )
			return getCallsub().compareTo( ((MobContagem)obj).getCallsub() );
		else if( obj instanceof MobCallsub )
			return getCallsub().compareTo( ((MobCallsub)obj).getCallsub() );
		return 0;
	}

	public String getCallsub() {
		return callsub;
	}

	public int getIdMob() {
		return idMob;
	}

	public void setIdMob(int idMob) {
		this.idMob = idMob;
	}

	public void setCallsub(String callsub) {
		this.callsub = callsub;
	}

	public String getReturn() {
		return _return;
	}

	public void setReturn(String _return) {
		this._return = _return;
	}

	public String getVarMobs() {
		return varMobs;
	}

	public void setVarMobs(String varMobs) {
		this.varMobs = varMobs;
	}

	public String getVarFlag() {
		return varFlag;
	}

	public void setVarFlag(String varFlag) {
		this.varFlag = varFlag;
	}

	public Integer getMax() {
		return max;
	}

	public void setMax(Integer max) {
		this.max = max;
	}

}
