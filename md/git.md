# add

该操作之后数据被从工作区提交的暂存区，告诉系统那些数据需要提交

# commit

将暂存区的改动添加到本地版本库中

# 撤销修改

1. git checkout --文件名 :文件还未放入暂存区，都在工作区，撤销工作区的修改，相当于回到最后一次commit状态
2. git reset 文件名：撤销指定文件的add操作，即这个文件在暂存区的修改

# commit操作的回退

1. git reset --hard HEAD^:回退到上个版本
2. git reset --hard HEAD^n:回退到前n次提交之前
3. git reset --hard commit_id:回退或者前进到指定的commit_id对应的版本

