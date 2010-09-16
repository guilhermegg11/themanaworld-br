package converter;

public class MobContagem implements Comparable<MobContagem> {

	private int idMob;
	private String callsub;
	private String _return;
	private String varMobs;
	private String varFlag;
	private Integer max;
	
	public MobContagem() {}

	public int compareTo(MobContagem cont) {
		return getCallsub().compareTo(cont.getCallsub());
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
